@extends('layouts.app')

@section('title', 'SceneTrip - ホーム')

@push('scripts')
@vite(['resources/ts/home.ts'])
@endpush

@section('content')
<div class="map-area">
	<div id="map"></div>
</div>

{{-- ▼▼▼ 検索フォームエリア ▼▼▼ --}}
<div class="general-box form-container" style="padding-top: 0; padding-bottom: 20px; margin: 16px 5% 16px; overflow: hidden;">

	{{-- 1. タブ切り替えボタン --}}
	<div class="home-btns">
		<button type="button" id="tab-btn-keyword"
			style="background: #fff; border-bottom-color: #16a34a; color: #16a34a;">
			🔍 キーワード検索
		</button>
		<button type="button" id="tab-btn-ai">
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

				<button type="submit" class="btn-green">
					検索する
				</button>
			</form>
			<p style="font-size: 0.8rem; color: #666; margin-top: 10px; text-align: center;">
				スポット名や作品名から探せます。
			</p>
		</div>

		{{-- 3. AI検索フォーム --}}
		<div id="form-area-ai" style="display: none;">

			{{-- ▼▼▼ ログイン済みの場合：フォームを表示 ▼▼▼ --}}
			@auth
				<form action="{{ route('ai.plan') }}" method="GET">
					<div style="background-color: #eff6ff; padding: 10px; border-radius: 4px; margin-bottom: 15px; font-size: 0.9rem; color: #1e40af;">
						<strong>🤖 AIプランナー:</strong> <br>出発地か目的地を入力すると、最適な寄り道スポットを提案します。
					</div>

					<div>
						<label for="ai_departure">出発地 <span>(どちらか必須)</span></label>
						<input type="text" id="ai_departure" name="departure" placeholder="例: 高知駅" />
					</div>

					<div>
						<label for="ai_destination">目的地 <span>(どちらか必須)</span></label>
						<input type="text" id="ai_destination" name="destination" placeholder="例: 桂浜" />
					</div>

					<button type="submit" style="width:100%; padding:12px; border:none; cursor:pointer; background: linear-gradient(to right, #2563eb, #7c3aed); color: white; font-weight: bold; border-radius: 4px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);">
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
					<a href="{{ route('login') }}" style="display: inline-block; background-color: #16a34a; color: white; padding: 10px 20px; border-radius: 4px; text-decoration: none; font-weight: bold;">
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

	<ul class="spot-list home-spot" aria-label="人気のスポット一覧">

		@if(count($displaySpots) > 0)
			@foreach($displaySpots as $index => $spot)
				<li class="spot-item">

					{{-- 2. 全体をリンク(aタグ)で囲んで詳細画面へ飛べるようにする --}}
					<a href="{{ route('detail', ['id' => $spot->id]) }}">

						{{-- 順位バッジ --}}
						<div style="background: {{ $index < 3 ? '#eab308' : '#9ca3af' }};">
							{{ $index + 1 }}
						</div>

						{{-- 画像 (▼▼▼ 修正: onerrorで代替画像を指定 ▼▼▼) --}}
						<img class="spot-thumb"
							 src="{{ asset('images/' . $spot->name . '.' . ($spot->img_ext ?? 'jpg')) }}"
							 alt="{{ $spot->name }}"
							 onerror="this.src='{{ asset('images/no-image.png') }}'"
							 onmouseover="this.style.opacity='0.8'"
							 onmouseout="this.style.opacity='1.0'" />

						<div class="spot-content">
							<h3 class="spot-title">{{ $spot->name }}</h3>
							<p>
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

<div class="suggest"></div>
@endsection
