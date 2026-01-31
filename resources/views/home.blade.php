@extends('layouts.app')

@section('title', 'ホーム')

@push('scripts')
	@vite(['resources/ts/home.ts'])
@endpush

@section('content')
<div class="map-area">
	<div id="map" data-spots="{{ json_encode($allSpots) }}"></div>
</div>

{{-- ▼▼▼ 検索フォームエリア ▼▼▼ --}}
<div class="general-box form-container" style="padding-top: 0; padding-bottom: 20px; margin: 16px auto 16px; overflow: hidden;">

	{{-- 1. タブ切り替えボタン --}}
	<div class="home-btns">
		<button type="button" id="tab_btn_keyword"
			style="background: #fff; border-bottom: 3px solid #108a66; color: #108a66; font-weight: bold;">
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

				{{-- 検索ボタン (クラスで統一色を適用) --}}
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
						<label for="ai_departure">出発地<span>（どちらか必須）</span></label>
						<input type="text" id="ai_departure" name="departure" placeholder="例: 高知駅" autocomplete="off">
						<div id="ai_departure_suggest"></div>
					</div>

					<div>
						<label for="ai_destination">目的地<span>（どちらか必須）</span></label>
						<input type="text" id="ai_destination" name="destination" placeholder="例: 桂浜" autocomplete="off">
						<div id="ai_destination_suggest"></div>
					</div>

					<div>
						<label for="ai_prompt">質問内容<span class="form-detail">（空欄の場合、特に条件を絞らずおすすめのスポットを提案します）</span></label>
						<input type="text" id="ai_prompt" name="prompt" placeholder="例: この間にある観光スポットを推薦して" />
					</div>

					<button type="submit" style="width:100%; padding:12px; border:none; cursor:pointer; background: #2563eb; color: white; font-weight: bold; border-radius: 4px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);">
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
					{{-- ログインボタン (クラスで統一色を適用) --}}
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

	{{-- 1. 見出しを「TOP5」に変更 --}}
	<h2 style="display: flex; align-items: center; gap: 10px;">
		<span style="color: #eab308;">🏆</span> 人気のスポット TOP5
	</h2>
	<div class="spot-divider" aria-hidden="true"></div>

	{{-- コントローラー変数の揺らぎ吸収 --}}
	@php
		$displaySpots = $rankingSpots ?? ($spots ?? []);
	@endphp

	<div class="spot-list home-spot" aria-label="人気のスポット一覧">

		@if(count($displaySpots) > 0)
			@foreach ($displaySpots as $index => $spot)
				<a class="spot-item" href="{{ route('detail', ['id' => $spot->id]) }}">

					{{-- 順位バッジ --}}
					<div style="background: {{ $index < 3 ? '#eab308' : '#9ca3af' }};">
						{{ $index + 1 }}
					</div>

					<img class="spot-thumb"
						 src="{{ isset($spot->img_ext) ? asset('storage/spots/' . $spot->id . '.' . $spot->img_ext) : asset('images/no-image.svg') }}"
						 alt="{{ $spot->name }}"
						 onmouseover="this.style.opacity='0.8'"
						 onmouseout="this.style.opacity='1.0'">

					<div class="spot-content">
						<h3 class="spot-title">{{ $spot->name }}</h3>
						{{-- 詳細を見る (クラスで統一色を適用) --}}
						<p class="text-green-link">
							詳細を見る ➜
						</p>
					</div>
				</a>
			@endforeach
		@else
			{{-- データがない場合 --}}
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
