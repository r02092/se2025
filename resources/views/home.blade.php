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

        {{-- 3. AI検索エリア (準備中表示) --}}
        <div id="form-area-ai" style="display: none; text-align: center; padding: 20px 0;">

            <div style="font-size: 3rem; margin-bottom: 10px;">🚧</div>

            <h3 style="font-weight: bold; color: #333; margin-bottom: 10px;">AI機能は準備中です</h3>

            <p style="color: #666; font-size: 0.9rem; line-height: 1.6; margin-bottom: 20px;">
                出発地と目的地を入力するだけで、<br>
                AIがおすすめの「寄り道プラン」を提案する機能を開発中です。<br>
                公開まで今しばらくお待ちください。
            </p>

            <button type="button" disabled
                style="width:100%; padding:12px; border:none; background-color: #e5e7eb; color: #9ca3af; font-weight: bold; border-radius: 4px; cursor: not-allowed;">
                Coming Soon...
            </button>
        </div>

    </div>
</div>

<div class="general-box ai-suggest" style="padding-bottom: auto;">
	<h2>人気のスポット</h2>
	<div class="spot-divider" aria-hidden="true"></div>

	<ul class="spot-list" aria-label="人気のスポット一覧">

		{{-- コントローラーから $spots データが渡ってきているかチェック --}}
		@if(isset($spots) && count($spots) > 0)
			@foreach($spots as $spot)
				<li class="spot-item">
					{{-- 画像パスがあればそれを、なければデフォルト画像（例:はりまや橋）を表示 --}}
					<img class="spot-thumb"
						 src="{{ asset($spot->image_path ?? 'images/Harimaya_Bridge.jpg') }}"
						 alt="{{ $spot->name }}"
						 {{-- 画像読み込み失敗時のフォールバック --}}
						 onerror="this.src='{{ asset('images/Harimaya_Bridge.jpg') }}'" />

					<div class="spot-content">
						<h3 class="spot-title">{{ $spot->name }}</h3>
						{{-- 検索回数を表示したい場合はコメントアウトを外してください --}}
						{{-- <p style="font-size:0.8rem; color:#16a34a;">検索数: {{ $spot->search_count }}回</p> --}}
					</div>
				</li>
			@endforeach
		@else
			{{-- データがまだ1件もない場合の表示 --}}
			<li class="spot-item">
				<div class="spot-content">
					<h3 class="spot-title">データ集計中...</h3>
					<p>いろいろな場所を検索してみてください。</p>
				</div>
			</li>
		@endif

	</ul>
</div>

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
