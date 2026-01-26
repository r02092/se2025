@extends('layouts.app')

@section('title', 'クーポンQR')

@push('scripts')
@vite(['resources/ts/coupon_qr.ts'])
@endpush

@section('content')
<main class="main-area qr-page" role="main">
	<div class="coupon-titlebar">
		<h1>クーポン</h1>
	</div>

	<!-- HERO（上部の大きな画像） -->
	<section class="hero">
		<img src="{{ asset('images/ryugado.jpg') }}" alt="Harimaya Bridge" class="hero-img">
	</section>

	<!-- クーポン詳細（背景に直接描画するスタイル） -->
	<article class="coupon-detail">
		<h2 class="coupon-detail-title">{{ $coupon->title ?? '龍河洞入場割引' }}</h2>
		<p class="coupon-detail-desc">
			{{ $coupon->description ?? '入場料100円引き。スタッフに画面を見せてください。' }}
		</p>
		<div class="coupon-detail-meta">
			<span class="coupon-tag">{{ $coupon->category ?? '観光' }}</span>
			<span class="coupon-exp">〜{{ $coupon->expiry_date ?? '2025/06/30' }}</span>
		</div>
		<!-- 必要に応じて追記する本文行など -->
		<p class="coupon-more">
			このクーポンはウェブ限定の割引です。提示はスタッフに画面を見せて確認してください。
		</p>
	</article>

	<!-- 下部固定の大きなスライダー（枠いっぱい、内部に背景と文言） -->
	<div class="coupon-use-button-area">
		<div class="coupon-use-button-active" aria-label="クーポン 利用中">
			クーポン 利用中
		</div>
	</div>
</main>

<!-- オーバーレイ（ポップアップ用） -->
<div id="qr_overlay" class="qr-overlay" aria-hidden="true">
	<div
		class="qr-modal"
		role="dialog"
		aria-modal="true"
		aria-labelledby="qr-modal-title"
	>
		<h2 class="qr-modal-title">{{ $coupon->title ?? '龍河洞 入場割引' }}</h2>
		<p class="qr-modal-sub">
			こちらの二次元コードをスタッフにご提示ください
		</p>
		<div class="qr-modal-image">
			<img
				src="{{ asset('images/coupon-code.gif') }}"
				alt="クーポンQRコード"
				style="image-rendering: pixelated"
			>
		</div>
		<p class="qr-modal-hint">
			この画面をスタッフに見せて割引を受けてください。
		</p>
		<div class="qr-modal-actions">
			<a class="qr-modal-close-btn" href="{{ route('coupon', $coupon->id ?? 1) }}">閉じる</a>
		</div>
	</div>
</div>
@endsection
