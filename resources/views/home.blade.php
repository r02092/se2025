@extends('layouts.app')

@section('title', 'ホーム')

@push('scripts')
    @vite(['resources/ts/home.ts'])
@endpush

{{-- ▼▼▼ スタイル定義 ▼▼▼ --}}
@push('styles')
<style>
    /* 緑色のボタン（キーワード検索、ログイン等） */
    .btn-green {
        width: 100%;
        padding: 12px;
        border: none;
        cursor: pointer;
        background-color: #16a34a; /* 緑色 */
        color: white;
        font-weight: bold;
        border-radius: 4px;
        transition: background-color 0.3s;
    }
    .btn-green:hover {
        background-color: #15803d;
    }

    /* AI検索用の青色ボタン */
    .btn-blue {
        width: 100%;
        padding: 12px;
        border: none;
        cursor: pointer;
        background-color: #2563eb; /* 青色 */
        color: white;
        font-weight: bold;
        border-radius: 4px;
        transition: background-color 0.3s;
    }
    .btn-blue:hover {
        background-color: #1d4ed8;
    }

    /* ログインボタンリンク */
    .btn-login-link {
        display: inline-block;
        background-color: #16a34a; /* 緑色 */
        color: white;
        padding: 10px 20px;
        border-radius: 4px;
        text-decoration: none;
        font-weight: bold;
        transition: background-color 0.3s;
    }
    .btn-login-link:hover {
        background-color: #15803d;
    }

    /* 「詳細を見る」のテキスト色 */
    .text-green-link {
        font-size: 0.8rem;
        color: #16a34a;
        text-align: right;
        margin-top: 5px;
        font-weight: bold;
    }
</style>
@endpush

@section('content')
<div class="map-area">
    <div id="map" data-spots="{{ json_encode($allSpots) }}"></div>
</div>

{{-- ▼▼▼ 検索フォームエリア ▼▼▼ --}}
<div class="general-box form-container" style="padding-top: 0; padding-bottom: 20px; margin: 16px 5% 16px; overflow: hidden;">

    {{-- 1. タブ切り替えボタン --}}
    <div class="home-btns">
        <button type="button" id="tab_btn_keyword"
            style="background: #fff; border-bottom: 3px solid #16a34a; color: #16a34a; font-weight: bold;">
            🔍 キーワード検索
        </button>
        <button type="button" id="tab_btn_ai">
            🤖 AIに聞く
        </button>
    </div>

    <div style="padding: 20px 15px 0;">

        {{-- 2. キーワード検索フォーム (初期表示) --}}
        <div id="form_area_keyword">
            <form action="{{ route('search') }}" method="GET">
                <div style="margin-bottom: 15px;">
                    <label for="destination" style="font-weight:bold; display:block; margin-bottom:5px;">目的地・キーワード</label>
                    <input type="text" id="destination" name="destination" placeholder="作品名・地名・キーワードを入力"
                           style="width:100%; padding:10px; border:1px solid #ccc; border-radius:4px; font-size:16px;" required>
                </div>

                {{-- キーワード検索ボタン：緑色 --}}
                <button type="submit" class="btn-green">
                    検索する
                </button>
            </form>
            <p style="font-size: 0.8rem; color: #666; margin-top: 10px; text-align: center;">
                スポット名や作品名から探せます。
            </p>
        </div>

        {{-- 3. AI検索フォーム --}}
        <div id="form_area_ai" style="display: none;">

            {{-- ▼▼▼ ログイン済みの場合：フォームを表示 ▼▼▼ --}}
            @auth
                <form action="{{ route('ai.plan') }}" method="GET">
                    <div style="background-color: #eff6ff; padding: 10px; border-radius: 4px; margin-bottom: 15px; font-size: 0.9rem; color: #1e40af;">
                        <strong>🤖 AIプランナー:</strong> <br>出発地と目的地を入力すると、最適な寄り道スポットを提案します。
                    </div>

                    <div>
                        <label for="ai_departure">出発地 <span>（どちらか必須）</span></label>
                        <input type="text" id="ai_departure" name="departure" placeholder="例: 高知駅" autocomplete="off">
                        <div id="ai_departure_suggest"></div>
                    </div>

                    <div>
                        <label for="ai_destination">目的地 <span>（どちらか必須）</span></label>
                        <input type="text" id="ai_destination" name="destination" placeholder="例: 桂浜" autocomplete="off">
                        <div id="ai_destination_suggest"></div>
                    </div>

                    <div>
                        <label for="ai_prompt">質問内容 <span class="form-detail">（空欄の場合、特に条件を絞らずおすすめのスポットを提案します）</span></label>
                        <input type="text" id="ai_prompt" name="prompt" placeholder="例: この間にある観光スポットを推薦して" />
                    </div>

                    {{-- ▼▼▼ 修正: btn-blueクラスに加え、直接スタイル指定で青色を強制 ▼▼▼ --}}
                    <button type="submit" class="btn-blue" style="background-color: #2563eb;">
                        AIにおすすめを聞く
                    </button>
                </form>
            @endauth

            {{-- ▼▼▼ 未ログインの場合：ログイン誘導を表示 ▼▼▼ --}}
            @guest
                <div style="text-align: center; padding: 30px 10px; background-color: #f9fafb; border-radius: 8px; border: 1px dashed #ccc;">
                    <div style="font-size: 3rem; margin-bottom: 10px;">🔒</div>
                    <h3 style="font-weight: bold; color: #333; margin-bottom: 10px;">ログインが必要です</h3>
                    <p style="color: #666; font-size: 0.9rem; margin-bottom: 20px;">
                        AIプランニング機能を利用するには、<br>ログインまたは会員登録を行ってください。
                    </p>
                    {{-- ログインボタン：緑色 --}}
                    <a href="{{ route('login') }}" class="btn-login-link">
                        ログイン画面へ
                    </a>
                </div>
            @endguest

        </div>

    </div>
</div>

{{-- ▼▼▼ 人気スポットエリア ▼▼▼ --}}
<div class="general-box ai-suggest" style="padding-bottom: auto;">

    <h2 style="display: flex; align-items: center; gap: 10px;">
        <span style="color: #eab308;">🏆</span> 人気のスポット TOP5
    </h2>
    <div class="spot-divider" aria-hidden="true"></div>

    @php
        $displaySpots = $rankingSpots ?? ($spots ?? []);
    @endphp

    <div class="spot-list home-spot" aria-label="人気のスポット一覧">

        @if(count($displaySpots) > 0)
            @foreach($displaySpots as $index => $spot)
                <a class="spot-item" href="{{ route('detail', ['id' => $spot->id]) }}">

                    <div style="background: {{ $index < 3 ? '#eab308' : '#9ca3af' }};">
                        {{ $index + 1 }}
                    </div>

                    <img class="spot-thumb"
                         src="{{ isset($spot->img_ext) ? ('storage/spots/' . $spot->id . '.' . $spot->img_ext) : asset('images/no-image.png') }}"
                         alt="{{ $spot->name }}"
                         onmouseover="this.style.opacity='0.8'"
                         onmouseout="this.style.opacity='1.0'">

                    <div class="spot-content">
                        <h3 class="spot-title">{{ $spot->name }}</h3>
                        {{-- 詳細リンク：緑色 --}}
                        <p class="text-green-link">
                            詳細を見る ➜
                        </p>
                    </div>
                </a>
            @endforeach
        @else
            <div class="spot-item">
                <div class="spot-content">
                    <h3 class="spot-title">集計中……</h3>
                    <p>検索データが集まるとランキングが表示されます。</p>
                </div>
            </div>
        @endif

    </div>
</div>
{{-- ▲▲▲ 人気スポットエリア（ここまで） ▲▲▲ --}}

<div class="suggest"></div>
@endsection
