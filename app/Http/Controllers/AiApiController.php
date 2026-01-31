<?php

namespace App\Http\Controllers;

use App\Http\Requests\AiApiRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\Query;
use App\Models\Spot;
use App\Models\Keyword;
use App\Traits\TransformCoordTrait;
use App\Traits\ToStringTrait;
use App\Traits\DistanceCalculatorTrait;

class AiApiController extends Controller
{
    use TransformCoordTrait;
    use ToStringTrait;
    use DistanceCalculatorTrait;

    public function post(AiApiRequest $request)
    {
        // 1. ログ保存
        Query::create([
            'user_id' => Auth::id(),
            'query' => $request->input('chat'),
            'from_spot_id' => $request->input('from'),
            'to_spot_id' => $request->input('to'),
            'ip_addr' => $request->ip() ?? '192.0.2.0',
            'port' => $request->getPort() ?? 0,
            'user_agent' => $request->userAgent() ?? '',
        ]);

        // 2. スポット情報の取得 (存在しない場合は null になる)
        $from = $request->input('from')
            ? Spot::find($request->input('from'))
            : null;
        $to = $request->input('to') ? Spot::find($request->input('to')) : null;

        // 3. 候補スポットの検索
        $query = Spot::query();

        // 自分自身を候補から除外
        if ($from) {
            $query->where('id', '<>', $from->id);
        }
        if ($to) {
            $query->where('id', '<>', $to->id);
        }

        if ($from && $to) {
            // パターンA: 【出発地と目的地がある場合】
            // 2点間の長方形範囲内を探す
            $minLng = min($from->lng, $to->lng);
            $maxLng = max($from->lng, $to->lng);
            $minLat = min($from->lat, $to->lat);
            $maxLat = max($from->lat, $to->lat);

            $query
                ->where('lng', '>=', $this->encodeLng($minLng))
                ->where('lng', '<=', $this->encodeLng($maxLng))
                ->where('lat', '>=', $this->encodeLat($minLat))
                ->where('lat', '<=', $this->encodeLat($maxLat));
        } elseif ($from || $to) {
            // パターンB: 【どちらか片方だけの場合】
            // その地点の周辺（半径 約10km程度）を探す

            // 基準となるスポットを特定（fromがあればfrom、なければto）
            $center = $from ?: $to;

            // 緯度経度 +/- 0.1度 の範囲で検索
            $query
                ->where('lng', '>=', $this->encodeLng($center->lng - 0.1))
                ->where('lng', '<=', $this->encodeLng($center->lng + 0.1))
                ->where('lat', '>=', $this->encodeLat($center->lat - 0.1))
                ->where('lat', '<=', $this->encodeLat($center->lat + 0.1));
        } else {
            // パターンC: 【両方ない場合】 (バリデーションで弾かれるはずだが念のため)
            return response()->json(
                ['error' => '検索基準となるスポットが指定されていません。'],
                400,
            );
        }

        $spots = $query->get();

        if ($spots->count() < 1) {
            return response()->json(
                ['error' => '候補となるスポットが見つかりませんでした。'],
                400,
            );
        }

        // 4. AIへのプロンプト作成
        $prompt = 'あなたは優秀な観光プランナーです。';
        $prompt .= 'これから示すスポットの情報を基に、';
        $prompt .= "ユーザーの質問に対して、正確な情報を提供してください。\n\n";
        $prompt .= 'スポットの情報の「キーワード」は、';
        $prompt .= "そのスポットが登場する作品などを表しています。\n\n";

        // 出発地の情報 (あれば追加)
        if ($from) {
            $prompt .= "ユーザーが入力した出発地は以下に示すスポットです:\n\n";
            $prompt .= $this->spotToMarkdown($from);
            $prompt .= "\n";
        }

        // 目的地の情報 (あれば追加)
        if ($to) {
            $prompt .= "ユーザーが入力した目的地は以下に示すスポットです:\n\n";
            $prompt .= $this->spotToMarkdown($to);
            $prompt .= "\n";
        }

        $prompt .= 'ユーザーにスポットを推薦する場合は、';
        $prompt .= "以下の中から選んでください:\n\n";
        foreach ($spots as $spot) {
            $prompt .= $this->spotToMarkdown($spot);
        }
        $prompt .= "\n";
        $prompt .= '回答の**1行目**は、推薦するスポットの**IDのみ**を';
        $prompt .= 'カンマ区切りで並べたものにしてください。';
        $prompt .= '例えば`1,2,4`のようにします。';
        $prompt .= "この行は機械的に処理され、ユーザーに表示されません。\n\n";
        $prompt .= '**2行目から**は、推薦する理由を';
        $prompt .= 'Markdown形式かつ日本語で、魅力的に書いてください。';
        $prompt .= '文中には推薦するスポットの名前を含めてください。\n\n';
        $prompt .= '文中でスポットの名前を言及する際は、';
        $prompt .= '`[スポットの名前](/detail?id=スポットのID)`の形式で';
        $prompt .= 'リンクを張ってください。';
        $prompt .= '文中では「キーワード」という言葉を避けてください。';

        // 5. APIコール (変更なし)
        preg_match(
            '/^\n*([,\d]+\d)\n(.+)$/s',
            json_decode(
                env('OPENROUTER_API_KEY')
                    ? file_get_contents(
                        'https://openrouter.ai/api/v1/chat/completions',
                        false,
                        stream_context_create([
                            'http' => [
                                'method' => 'POST',
                                'header' => [
                                    'Content-Type: application/json',
                                    'Authorization: Bearer ' .
                                    env('OPENROUTER_API_KEY'),
                                ],
                                'content' => json_encode([
                                    'model' =>
                                        'tngtech/deepseek-r1t2-chimera:free',
                                    'messages' => [
                                        [
                                            'role' => 'system',
                                            'content' => $prompt,
                                        ],
                                        [
                                            'role' => 'user',
                                            'content' => $request->input(
                                                'chat',
                                            ),
                                        ],
                                    ],
                                    'reasoning' => [
                                        'effort' => 'none',
                                    ],
                                ]),
                            ],
                        ]),
                    )
                    : '{"choices":[{"message":{"content":"3,4\nテスト用の回答です。"}}]}',
            )->choices[0]->message->content,
            $matches,
        );

        if (isset($matches[1])) {
            $recommendedSpots = [];
            $dists = [];
            foreach (explode(',', $matches[1]) as $id) {
                $spot = Spot::find((int) $id, [
                    'id',
                    'name',
                    'type',
                    'lng',
                    'lat',
                    'postal_code',
                    'addr_city',
                    'addr_detail',
                    'description',
                    'img_ext',
                    'shows',
                ]);
                if ($spot) {
                    $recommendedSpots[] = $spot;
                    if ($from && $to) {
                        $dist = $this->calculateDistance(
                            $from->lat,
                            $from->lng,
                            $spot->lat,
                            $spot->lng,
                        );
                        $dists[] = [
                            $spot,
                            $dist /
                            ($dist +
                                $this->calculateDistance(
                                    $to->lat,
                                    $to->lng,
                                    $spot->lat,
                                    $spot->lng,
                                )),
                        ];
                    }
                    $spot->shows++;
                    $spot->save();
                }
                if ($from && $to) {
                    array_multisort($dists, $recommendedSpots);
                }
            }
        } else {
            return response()->json([
                'error' => 'LLMから適切な回答が得られませんでした。',
                400,
            ]);
        }
        return response()->json([
            'recommended_spots' => $recommendedSpots,
            'explanation' => preg_replace('/^\n+/', '', $matches[2]),
        ]);
    }

    private function spotToMarkdown(Spot $spot)
    {
        $md = "- **{$spot->name}**\n";
        $md .= "  - **ID**: {$spot->id}\n";
        $md .= "  - **種類**: {$this->spotTypeToString($spot->type)}\n";
        $md .= "  - **説明**: {$spot->description}\n";
        $md .= '  - **キーワード**: ';
        $md .=
            implode(
                ', ',
                Keyword::where('spot_id', $spot->id)
                    ->pluck('keyword')
                    ->toArray(),
            ) . "\n";
        $md .= '  - **住所**: ';
        if ($spot->postal_code) {
            $md .= "{$this->postalCodeToString($spot->postal_code)} ";
        }
        if ($spot->addr_city) {
            $md .= $this->cityToString($spot->addr_city);
        }
        if ($spot->addr_detail) {
            $md .= $spot->addr_detail;
        }
        $md .= "\n";
        return $md;
    }
}
