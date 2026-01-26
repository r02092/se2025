@extends('layouts.app')

@section('title', '検索結果')

@section('content')

{{-- ▼▼▼ ハイライト用のヘルパー関数（強化版） ▼▼▼ --}}
@php
    // ハイライト処理関数
    function highlightKeywords($text, $searchQuery) {
        // オブジェクト対策
        if (is_object($text)) {
            $text = $text->keyword ?? '';
        }
        if (is_array($text)) {
            $text = $text['keyword'] ?? '';
        }

        if (empty($text) || empty($searchQuery)) {
            return e($text);
        }

        // 1. 検索ワードをスペースで分割
        $rawKeywords = preg_split('/[\s]+/', mb_convert_kana($searchQuery, 's'), -1, PREG_SPLIT_NO_EMPTY);

        if (empty($rawKeywords)) {
            return e($text);
        }

        // 2. 表記ゆれ（ひらがな・カタカナ）のパターンを生成
        $patterns = [];
        foreach ($rawKeywords as $word) {
            // HTMLエスケープしてから正規表現用にエスケープ
            $eWord = e($word);

            // パターンA: そのまま
            $patterns[] = preg_quote($eWord, '/');

            // パターンB: 全角ひらがな -> 全角カタカナ (例: りんご -> リンゴ)
            $kata = mb_convert_kana($word, 'C');
            $patterns[] = preg_quote(e($kata), '/');

            // パターンC: 全角カタカナ -> 全角ひらがな (例: リンゴ -> りんご)
            $hira = mb_convert_kana($word, 'c');
            $patterns[] = preg_quote(e($hira), '/');

            // パターンD: 半角カタカナ -> 全角カタカナ
            $zenKata = mb_convert_kana($word, 'KV');
            $patterns[] = preg_quote(e($zenKata), '/');
        }

        // 重複を除外
        $patterns = array_unique($patterns);

        // 3. 正規表現を作成 (例: /(りんご|リンゴ)/iu )
        $regex = '/(' . implode('|', $patterns) . ')/iu';

        // 4. テキスト全体をエスケープしてから、一括でハイライト置換
        // これにより、検索語句自体がひらがなでもカタカナでもヒット箇所が黄色くなります
        return preg_replace(
            $regex,
            '<strong style="background: #fef08a; color: #854d0e; padding: 0 2px; border-radius: 2px;">$1</strong>',
            e($text)
        );
    }
@endphp

<div class="container" style="max-width: 800px; margin: 0 auto; padding: 30px 20px;">

    {{-- ヘッダー --}}
    <div style="margin-bottom: 30px;">
        <h1 style="font-size: 1.5rem; font-weight: bold; color: #333; margin-bottom: 10px;">
            🔍 検索結果
        </h1>
        <p style="color: #666;">
            @if($departure)
                「<strong>{{ $departure }}</strong>」周辺、かつ
            @endif
            「<strong>{{ $destination }}</strong>」を含むスポット
        </p>
    </div>

    {{-- エラー表示 --}}
    @if($departureNotFound)
        <div style="background: #fef2f2; border: 1px solid #fecaca; color: #b91c1c; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
            <strong>⚠️ 出発地が見つかりませんでした</strong><br>
            出発地「{{ $departure }}」の位置情報が取得できませんでした。<br>
            キーワード「{{ $destination }}」のみでの検索結果を表示しています。
        </div>
    @endif

    {{-- 検索結果リスト --}}
    <div class="search-results">
        @if(count($spots) > 0)
            @foreach($spots as $spot)
                <div class="result-card" style="background: #fff; border: 1px solid #e5e7eb; border-radius: 12px; overflow: hidden; margin-bottom: 20px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); transition: transform 0.2s;">

                    {{-- カード全体をリンクにする --}}
                    <a href="{{ route('detail', ['id' => $spot->id]) }}" style="text-decoration: none; color: inherit; display: flex; flex-direction: column; sm:flex-direction: row;">

                        {{-- 1. 画像エリア --}}
                        <div style="height: 200px; background: #f3f4f6; position: relative; overflow: hidden;">
                            <img src="{{ $spot->image_url ?? asset('images/no-image.png') }}"
                                 alt="{{ $spot->name }}"
                                 onerror="this.src='{{ asset('images/no-image.png') }}'"
                                 style="width: 100%; height: 100%; object-fit: cover;">
                        </div>

                        {{-- 2. 情報エリア --}}
                        <div style="padding: 20px; flex: 1; display: flex; flex-direction: column;">

                            {{-- タイトル --}}
                            <h2 style="font-size: 1.25rem; font-weight: bold; color: #333; margin: 0 0 10px 0;">
                                {!! highlightKeywords($spot->name, $destination) !!}
                            </h2>

                            {{-- 説明文 --}}
                            <p style="font-size: 0.9rem; color: #666; line-height: 1.6; margin-bottom: 15px; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;">
                                {!! highlightKeywords($spot->description, $destination) !!}
                            </p>

                            {{-- キーワードタグ --}}
                            @if(!empty($spot->keywords))
                                <div style="margin-bottom: 20px; display: flex; flex-wrap: wrap; gap: 8px;">
                                    @foreach($spot->keywords as $keyword)
                                        <span style="font-size: 0.8rem; background: #f3f4f6; color: #555; padding: 4px 10px; border-radius: 20px;">
                                            # {!! highlightKeywords($keyword, $destination) !!}
                                        </span>
                                    @endforeach
                                </div>
                            @endif

                            {{-- 3. アクションボタン --}}
                            <div style="margin-top: auto;">
                                <span style="display: inline-block; background-color: #16a34a; color: white; padding: 10px 20px; border-radius: 6px; font-weight: bold; font-size: 0.95rem; text-align: center; transition: background 0.2s;">
                                    詳細を見る
                                </span>
                            </div>

                        </div>
                    </a>
                </div>
            @endforeach
        @else
            {{-- ヒットなし --}}
            <div style="text-align: center; padding: 60px 20px; background: #f9fafb; border-radius: 12px;">
                <p style="font-size: 4rem; margin-bottom: 20px;">😢</p>
                <h3 style="font-weight: bold; color: #333; margin-bottom: 10px;">見つかりませんでした</h3>
                <p style="color: #666;">
                    キーワードを変えて、もう一度検索してみてください。
                </p>
                <div style="margin-top: 30px;">
                    <a href="/" style="color: #16a34a; font-weight: bold; text-decoration: underline;">ホームに戻る</a>
                </div>
            </div>
        @endif
    </div>

    {{-- 再検索ボタン --}}
    <div style="margin-top: 40px; text-align: center;">
        <a href="/" style="display: inline-block; background: #fff; border: 1px solid #ccc; color: #333; padding: 12px 30px; border-radius: 30px; text-decoration: none; font-weight: bold; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
            条件を変えて再検索
        </a>
    </div>

</div>

<style>
    @media (min-width: 640px) {
        .result-card a {
            flex-direction: row !important;
        }
        .result-card img {
            width: 240px !important;
            height: 100% !important;
        }
    }
    .result-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 15px rgba(0,0,0,0.1) !important;
    }
    .result-card:hover span[style*="background-color: #16a34a"] {
        background-color: #15803d !important;
    }
</style>

@endsection
