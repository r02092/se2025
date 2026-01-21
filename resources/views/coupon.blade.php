@extends('layouts.app')

@section('title', 'SceneTrip - クーポン')

@section('content')
<h1>クーポン</h1>

<div class="coupon-filter" role="region" aria-label="クーポンフィルタ">
	<div class="chip-group" role="tablist" aria-label="クーポンカテゴリ">
		<button class="chip active" data-cat="all" aria-pressed="true">すべて</button>
		<button class="chip" data-cat="food" aria-pressed="false">飲食</button>
		<button class="chip" data-cat="shop" aria-pressed="false">お土産</button>
		<button class="chip" data-cat="attraction" aria-pressed="false">観光</button>
		<button class="chip" data-cat="experience" aria-pressed="false">体験アクティビティ</button>
		<button class="chip" data-cat="stay" aria-pressed="false">宿泊</button>
	</div>

	<div class="sort-wrap">
		<div class="sort-select">
			<label for="sort">並び替え</label>
			<select id="sort" name="sort">
				<option value="recommended">おすすめ順</option>
				<option value="new">新着順</option>
				<option value="exp">期限が近い順</option>
			</select>
		</div>

		<div class="sort-select" style="margin-left: 12px">
			<label for="view-select">表示</label>
			<select id="view-select" name="view-select" aria-controls="active-coupons available-coupons" aria-label="クーポン表示切替">
				<option value="all">すべて</option>
				<option value="active">現在利用中のクーポン</option>
				<option value="available">利用可能なクーポン</option>
			</select>
		</div>

		<div id="view-live" class="sr-only" aria-live="polite" aria-atomic="true"></div>
	</div>
</div>

<section class="coupon-list" aria-label="クーポン一覧">
	@foreach ($couponsList as $coupons)
	@if(isset($coupons[2]))
	<section id="{{ $coupons[1] }}" aria-label="{{ $coupons[0] }}クーポン">
		<h2>{{ $coupons[0] }}クーポン</h2>
		@foreach($coupons[2] as $coupon)
		<article class="general-box coupon-card" data-category="{{ $coupon[0]->type }}">
			<div class="coupon-info">
				<h3 class="coupon-name">{{ $coupon[0]->name }}</h3>
				<p class="coupon-exp">スポット: {{ $coupon[0]->spot->name }}</p>
				<p class="coupon-exp">種別: {{ $coupon[1] }}</p>
				<p class="coupon-exp">有効期限: {{ isset($coupon[0]->expires_at) ? $coupon[0]->expires_at->format('Y年m月d日') : 'なし' }}</p>
			</div>
			<a href="{{ route('coupon.show', $coupon[0]->id) }}" class="coupon-btn">{{ $coupons[0] == '現在利用中の' ? '詳細を見る' : '取得する' }}</a>
		</article>
		@endforeach
	</section>
	@endif
	@endforeach
</section>
@endsection
