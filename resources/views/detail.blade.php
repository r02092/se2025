@extends('layouts.app')

@section('title', $spot->name)

@push('scripts')
@vite(['resources/ts/detail.ts'])
@endpush

@section('content')
<div class="spot-detail-container">

	{{-- スポット基本情報カード --}}
	<article class="general-box spot-detail-card">

		{{-- タイトルとカテゴリ --}}
		<header class="spot-detail-header">
			<h1 class="spot-detail-title">{{ $spot->name }}</h1>
			<span class="spot-category-badge">{{ $typeStr }}</span>
		</header>

		{{-- スポット画像 --}}
		@if (isset($spot->img_ext))
			<div class="spot-detail-image-wrapper">
				<img src="{{ asset('storage/spots/' . $spot->id . '.' . $spot->img_ext) }}"
					alt="{{ $spot->name }}"
					id="img"
					class="spot-detail-image">
				<dialog id="dialog"><img src="{{ asset('storage/spots/' . $spot->id . '.' . $spot->img_ext) }}"></dialog>
			</div>
		@endif

		<div>
			{{-- スポットの説明 --}}
			<section>
				<h2>説明</h2>
				<div class="spot-detail-text">
					{{-- 改行コードを<br>に変換して表示 --}}
					{!! nl2br(e($spot->description)) !!}
				</div>
			</section>

			{{-- スポットの場所 --}}

			<section>
				<h2>住所</h2>
				<div class="spot-detail-text">
					<div>
						{{ $postal_code }}
					</div>
					<div>
						{{ $addrStr }}
					</div>
				</div>
			</section>
			{{-- スポットの場所 --}}
			<section>
				<h2>場所</h2>
				<div id="map" data-lng="{{ $spot->lng }}" data-lat="{{ $spot->lat }}"></div>
				<div>
					<a href="https://www.google.com/maps/search/?api=1&query={{ $spot->lat }},{{ $spot->lng }}"
					target="_blank"
					rel="noopener noreferrer"
					class="a-btn spot-detail-btn">
						Google マップで見る
					</a>
				</div>
			</section>

			{{-- 関連キーワード --}}
			@if($spot->keywords->isNotEmpty())
				<div>
					<h2>関連キーワード</h2>
					<div class="spot-detail-keywords">
						@foreach ($spot->keywords as $keyword)
							{{-- ▼▼▼ 変更箇所: span を a タグに変えて検索ページへリンク ▼▼▼ --}}
							<a href="{{ route('search', ['destination' => $keyword->keyword]) }}"
							   class="spot-detail-keyword-tag"
							   style="text-decoration: none; color: inherit; cursor: pointer;">
								{{ $keyword->keyword }}
							</a>
						@endforeach
					</div>
				</div>
			@endif
		</div>
	</article>

	{{-- ▼ 口コミ・評価エリア ▼ --}}
	<section class="general-box spot-detail-card spot-detail-review-section">
		<h2>口コミ・評判</h2>

		{{-- 評価の平均点表示 --}}
		<div class="spot-detail-rating-summary">
			@php
				// 平均評価の計算（Reviewがない場合は0）
				$avgRate = $spot->reviews->avg('rate') ?? 0;
				$starCount = round($avgRate);
			@endphp
			<span class="spot-detail-average-rate">
				<span style="color: #aaa;">平均評価 <span class="review-stars">★</span>{{ number_format($avgRate, 1) }}</span>
			</span>
			<div class="spot-detail-average-label">
				({{ $spot->reviews->count() }}件のレビュー)
			</div>
		</div>

		{{-- 口コミ一覧 --}}
		@if($spot->reviews->isNotEmpty())
		<div class="spot-detail-review-list">
				@foreach ($spot->reviews as $review)
				<div class="spot-detail-review-item">
					<div class="spot-detail-review-header">
						{{-- ユーザー名（Reviewモデルのuserメソッド経由） --}}
						<span class="spot-detail-review-user">{{ $review->user->name ?? '退会済みユーザー' }}</span>
							<span class="spot-detail-review-date">{{ $review->updated_at->format('Y/m/d') }}</span>
					</div>
					<div class="review-stars">
						{{-- 評価の星表示 --}}
						@for ($i = 1; $i <= 5; $i++)
							@if($i <= $review->rate) ★ @else <span>★</span> @endif
						@endfor
					</div>
					<div class="spot-detail-review-comment">
						{!! nl2br(e($review->comment)) !!}
					</div>
				</div>
				@endforeach
		</div>
		@else
			<p style="text-align: center; color: #555;">まだ口コミはありません。</p>
		@endif

		{{-- 口コミ投稿フォーム --}}
		<div class="spot-detail-review-list">
			<h3 style="margin-top: 0; margin-bottom: 1.5rem; font-size: 1.1rem;">口コミを投稿する</h3>

			{{-- ログイン済みの場合のみ表示 --}}
			@auth
				<form action="{{ route('reviews.store', $spot->id) }}" method="POST">
					@csrf
					{{-- spot_idを送信するためのhidden項目 --}}
					<input type="hidden" name="spot_id" value="{{ $spot->id }}">

					<div class="spot-detail-form-group">
						<label for="rate" class="spot-detail-form-label">評価</label>
						<select name="rate" id="rate" class="spot-detail-form-input" required>
							<option value="" disabled selected>選択してください</option>
							<option value="5">★★★★★ (5)</option>
							<option value="4">★★★★ (4)</option>
							<option value="3">★★★ (3)</option>
							<option value="2">★★ (2)</option>
							<option value="1">★ (1)</option>
						</select>
					</div>

					<div class="spot-detail-form-group">
						<label for="comment" class="spot-detail-form-label">コメント</label>
						<textarea name="comment" id="comment" rows="4" class="spot-detail-form-input" placeholder="スポットの感想を教えてください（1〜1000文字まで）" required></textarea>
					</div>

					@if($errors->any())
						<div class="form-error">
							<div>
								投稿に失敗しました
							</div>
							<ul class="spot-detail-errors">
								@foreach ($errors->all() as $error)
									<li>{{ $error }}</li>
								@endforeach
							</ul>
						</div>
					@endif

					<button type="submit" class="spot-detail-btn-submit">投稿する</button>
				</form>
			@else
				<div style="text-align: center; color: #777;">
					<p>口コミを投稿するには<a href="{{ route('login') }}" style="color: #3498db;">ログイン</a>が必要です。</p>
				</div>
			@endauth
		</div>

	</section>

</div>

@endsection
