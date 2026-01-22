@extends('layouts.app')

@section('title', 'SceneTrip - ホーム')

@push('styles')
@endpush

@section('content')
<!-- グラデーション トップ -->

<div class="map-area">
	<div id="map"></div>
</div>

{{-- ▼▼▼ 検索フォームエリア（ここから入れ替え） ▼▼▼ --}}
<div class="general-box form-container" style="padding-top: 0; padding-bottom: 20px; margin: 16px 5% 16px; overflow: hidden;">

    {{-- 1. タブ切り替えボタン --}}
    <div style="display: flex; border-bottom: 1px solid #eee; background: #f9fafb;">
        <button type="button" id="tab-btn-keyword" onclick="switchSearchTab('keyword')"
            style="flex: 1; padding: 15px; border: none; background: #fff; border-bottom: 3px solid #16a34a; font-weight: bold; color: #16a34a; cursor: pointer; transition: all 0.2s;">
            🔍 キーワード検索
        </button>
        <button type="button" id="tab-btn-ai" onclick="switchSearchTab('ai')"
            style="flex: 1; padding: 15px; border: none; background: #f3f4f6; border-bottom: 3px solid transparent; font-weight: bold; color: #6b7280; cursor: pointer; transition: all 0.2s;">
            🤖 AIに聞く
        </button>
    </div>

    <div style="padding: 20px 15px 0;">

        {{-- 2. キーワード検索フォーム (初期表示) --}}
        <div id="form-area-keyword">
            <form action="{{ route('search') }}" method="GET">
                <div style="margin-bottom: 15px;">
                    <label for="destination" style="font-weight:bold; display:block; margin-bottom:5px;">目的地・キーワード</label>
                    <input type="text" id="destination" name="destination" placeholder="作品名・地名・キーワードを入力"
                           style="width:100%; padding:10px; border:1px solid #ccc; border-radius:4px; font-size:16px;" required />
                </div>

                <button type="submit" class="btn-green" style="width:100%; padding:12px; border:none; cursor:pointer; background-color: #16a34a; color: white; font-weight: bold; border-radius: 4px;">
                    検索する
                </button>
            </form>
            <p style="font-size: 0.8rem; color: #666; margin-top: 10px; text-align: center;">
                スポット名や作品名から探せます。
            </p>
        </div>

        {{-- 3. AI検索フォーム --}}
        <div id="form-area-ai" style="display: none;">
            <form action="{{ route('ai.plan') }}" method="GET">
                <div style="background-color: #eff6ff; padding: 10px; border-radius: 4px; margin-bottom: 15px; font-size: 0.9rem; color: #1e40af;">
                    <strong>🤖 AIプランナー:</strong> 出発地から目的地までの「おすすめ寄り道スポット」を提案します。
                </div>

                <div style="margin-bottom: 15px;">
                    <label for="departure_name" style="font-weight:bold; display:block; margin-bottom:5px;">出発地 <span style="color:#e11d48; font-size:0.8rem;">(必須)</span></label>
                    <input type="text" id="departure_name" name="departure_name" placeholder="例: 高知駅" required
                           style="width:100%; padding:10px; border:1px solid #93c5fd; border-radius:4px; background-color: #f0f9ff; font-size:16px;" />
                </div>

                <div style="margin-bottom: 15px;">
                    <label for="destination_name" style="font-weight:bold; display:block; margin-bottom:5px;">目的地 <span style="color:#e11d48; font-size:0.8rem;">(必須)</span></label>
                    <input type="text" id="destination_name" name="destination_name" placeholder="例: 桂浜" required
                           style="width:100%; padding:10px; border:1px solid #93c5fd; border-radius:4px; background-color: #f0f9ff; font-size:16px;" />
                </div>

                <button type="submit" style="width:100%; padding:12px; border:none; cursor:pointer; background: linear-gradient(to right, #2563eb, #7c3aed); color: white; font-weight: bold; border-radius: 4px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);">
                    AIにおすすめを聞く
                </button>
            </form>
        </div>

    </div>
</div>

{{-- ▼▼▼ 人気スポットエリア（ここから入れ替え） ▼▼▼ --}}
<div class="general-box ai-suggest" style="padding-bottom: auto;">

    {{-- 1. 見出しを「TOP5」に変更 --}}
    <h2 style="display: flex; align-items: center; gap: 10px;">
        <span style="color: #eab308;">🏆</span> 人気のスポット TOP5
    </h2>
    <div class="spot-divider" aria-hidden="true"></div>

    <ul class="spot-list" aria-label="人気のスポット一覧">

        @if(isset($spots) && count($spots) > 0)
            @foreach($spots as $index => $spot)
                <li class="spot-item" style="position: relative; transition: transform 0.2s;">

                    {{-- 2. 全体をリンク(aタグ)で囲んで詳細画面へ飛べるようにする --}}
                    <a href="{{ route('detail', ['id' => $spot->id]) }}"
                       style="display: block; text-decoration: none; color: inherit; height: 100%;">

                        {{-- 順位バッジ（1位〜3位だけ色を変える演出） --}}
                        <div style="position: absolute; top: 0; left: 0; background: {{ $index < 3 ? '#eab308' : '#9ca3af' }}; color: white; font-weight: bold; padding: 4px 10px; border-radius: 4px 0 4px 0; z-index: 10;">
                            {{ $index + 1 }}
                        </div>

                        {{-- 画像 --}}
                        <img class="spot-thumb"
                             src="{{ asset($spot->image_path ?? 'images/Harimaya_Bridge.jpg') }}"
                             alt="{{ $spot->name }}"
                             onerror="this.src='{{ asset('images/Harimaya_Bridge.jpg') }}'"
                             style="transition: opacity 0.2s;"
                             onmouseover="this.style.opacity='0.8'"
                             onmouseout="this.style.opacity='1.0'" />

                        <div class="spot-content">
                            <h3 class="spot-title">{{ $spot->name }}</h3>
                            <p style="font-size: 0.8rem; color: #16a34a; text-align: right; margin-top: 5px;">
                                詳細を見る ➜
                            </p>
                        </div>
                    </a>
                </li>
            @endforeach
        @else
            {{-- データがない場合 --}}
            <li class="spot-item">
                <div class="spot-content">
                    <h3 class="spot-title">集計中...</h3>
                    <p>検索データが集まるとランキングが表示されます。</p>
                </div>
            </li>
        @endif

    </ul>
</div>
{{-- ▲▲▲ 人気スポットエリア（ここまで） ▲▲▲ --}}

{{-- ▼▼▼ タブ切り替え用のスクリプト ▼▼▼ --}}
<script>
    function switchSearchTab(tabName) {
        const btnKeyword = document.getElementById('tab-btn-keyword');
        const btnAi = document.getElementById('tab-btn-ai');
        const areaKeyword = document.getElementById('form-area-keyword');
        const areaAi = document.getElementById('form-area-ai');

        if (tabName === 'keyword') {
            areaKeyword.style.display = 'block';
            areaAi.style.display = 'none';

            btnKeyword.style.background = '#fff';
            btnKeyword.style.color = '#16a34a';
            btnKeyword.style.borderBottomColor = '#16a34a';

            btnAi.style.background = '#f3f4f6';
            btnAi.style.color = '#6b7280';
            btnAi.style.borderBottomColor = 'transparent';
        } else {
            areaKeyword.style.display = 'none';
            areaAi.style.display = 'block';

            btnAi.style.background = '#fff';
            btnAi.style.color = '#2563eb';
            btnAi.style.borderBottomColor = '#2563eb';

            btnKeyword.style.background = '#f3f4f6';
            btnKeyword.style.color = '#6b7280';
            btnKeyword.style.borderBottomColor = 'transparent';
        }
    }
</script>
{{-- ▲▲▲ 検索フォームエリア（ここまで） ▲▲▲ --}}

<div class="suggest"></div>
@endsection
