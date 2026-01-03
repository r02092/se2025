<?php

namespace App\Http\Controllers;

use App\Http\Requests\AiApiRequest;
use App\Models\Query;
use App\Models\Spot;
use App\Models\Keyword;
use App\Traits\TransformCoordTrait;
use App\Traits\ToStringTrait;

class AiApiController extends Controller
{
    use TransformCoordTrait;
    use ToStringTrait;

    public function post(AiApiRequest $request)
    {
        Query::create([
            'user_id' => auth()->id(),
            'query' => $request->input('chat'),
            'from_spot_id' => $request->input('from'),
            'to_spot_id' => $request->input('to'),
            'ip_addr' => $request->ip() ?? '192.0.2.0',
            'port' => $request->getPort() ?? 0,
            'user_agent' => $request->userAgent() ?? '',
        ]);
        if ($request->input('from') === $request->input('to')) {
            return response()->json(
                ['error' => '出発地と目的地が同じです。'],
                400,
            );
        }
        $from = Spot::find($request->input('from'));
        $to = Spot::find($request->input('to'));
        if (!$from) {
            return response()->json(
                ['error' => '指定されたスポットが見つかりません。'],
                400,
            );
        }
        if ($to) {
            $spots = Spot::where('id', '<>', $from->id)
                ->where('id', '<>', $to->id)
                ->where(
                    'lng',
                    '>=',
                    $this->encodeLng(min($from->lng, $to->lng)),
                )
                ->where(
                    'lng',
                    '<=',
                    $this->encodeLng(max($from->lng, $to->lng)),
                )
                ->where(
                    'lat',
                    '>=',
                    $this->encodeLat(min($from->lat, $to->lat)),
                )
                ->where(
                    'lat',
                    '<=',
                    $this->encodeLat(max($from->lat, $to->lat)),
                )
                ->get();
        } else {
            $spots = Spot::where('id', '<>', $from->id)
                ->where('lng', '>=', $this->encodeLng($from->lng - 0.1))
                ->where('lng', '<=', $this->encodeLng($from->lng + 0.1))
                ->where('lat', '>=', $this->encodeLat($from->lat - 0.1))
                ->where('lat', '<=', $this->encodeLat($from->lat + 0.1))
                ->get();
        }
        if ($spots->count() < 1) {
            return response()->json(
                ['error' => '候補となるスポットが見つかりませんでした。'],
                400,
            );
        }
        $prompt = 'あなたは優秀な観光プランナーです。';
        $prompt .= 'これから示すスポットの情報を基に、';
        $prompt .= "ユーザーの質問に対して、正確な情報を提供してください。\n";
        $prompt .= 'スポットの情報の「キーワード」は、';
        $prompt .= "そのスポットが登場する作品などを表しています。\n\n";
        $prompt .= 'ユーザーが入力した' . ($to ? '出発地' : 'スポット');
        $prompt .= "は以下に示すスポットです:\n";
        $prompt .= $this->spotToMarkdown($from);
        $prompt .= "\n";
        if ($to) {
            $prompt .= "ユーザーが入力した目的地は以下に示すスポットです:\n";
            $prompt .= $this->spotToMarkdown($to);
            $prompt .= "\n";
        }
        $prompt .= 'ユーザーにスポットを推薦する場合は、';
        $prompt .= "以下の中から選んでください:\n";
        foreach ($spots as $spot) {
            $prompt .= $this->spotToMarkdown($spot);
        }
        $prompt .= "\n";
        $prompt .= '回答の最初の行は、推薦するスポットのIDを';
        $prompt .= 'カンマ区切りで並べたものにしてください。';
        $prompt .= '例えば`1,2,4`のようにします。';
        $prompt .= "この行は機械的に処理され、ユーザーに表示されません。\n";
        $prompt .= '次の行からは、推薦する理由を';
        $prompt .= 'Markdown形式かつ日本語で説明してください。';
        $prompt .= '文中には推薦するスポットの名前を含めてください。\n';
        $prompt .= '文中でスポットの名前を言及する際は、';
        $prompt .= '`[スポットの名前](spots/スポットのID)`の形式で';
        $prompt .= 'リンクを張ってください。';
        $prompt .= '文中では「キーワード」という言葉を避けてください。';
        preg_match(
            '/^([,\d]+\d)\n(.+)$/s',
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
                                    'model' => 'xiaomi/mimo-v2-flash:free',
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
            foreach (explode(',', $matches[1]) as $id) {
                $spot = Spot::find((int) $id, [
                    'id',
                    'name',
                    'type',
                    'postal_code',
                    'addr_city',
                    'addr_detail',
                    'img_ext',
                ]);
                if ($spot) {
                    $recommendedSpots[] = $spot;
                }
            }
        } else {
            return response()->json([
                'error' => 'LLMから適切な回答が得られませんでした。',
            ]);
        }
        return response()->json([
            'recommended_spots' => $recommendedSpots,
            'explanation' => $matches[2] ?? '',
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
