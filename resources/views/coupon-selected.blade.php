@extends('layouts.app')

@section('title', 'クーポン')

@section('content')
<div class="main-area">
	<div class="coupon-titlebar">
		<h1>クーポン</h1>
	</div>

	<!-- HERO（上部の大きな画像） -->
	<section class="hero">
		<img src="{{ asset('images/ryugado.jpg') }}" alt="Harimaya Bridge" class="hero-img" />
	</section>

	<!-- クーポン詳細（背景に直接描画するスタイル） -->
	<article class="coupon-detail">
		<h1 class="coupon-detail-title">{{ $coupon->title ?? '龍河洞入場割引' }}</h1>
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
	<div class="coupon-use-slider" aria-hidden="false">
		<div class="slider-track" aria-hidden="true">
			<div class="slider-fill" aria-hidden="true"></div>
			<div
				class="slider-handle"
				role="button"
				tabindex="0"
				aria-label="スライドしてクーポン利用"
				href="{{ route('coupon.qr', $coupon->id ?? 1) }}"
			>
				＞＞＞
			</div>
			<div class="slider-label" aria-hidden="true">
				スライドでクーポン利用
			</div>
		</div>
	</div>

	<!-- QR オーバーレイ -->
	<div id="qr-overlay" class="qr-overlay" aria-hidden="true">
		<div
			class="qr-panel"
			role="dialog"
			aria-modal="true"
			aria-label="クーポンQR"
		>
			<button class="qr-close" aria-label="閉じる">&times;</button>
			<h2>クーポンを提示してください</h2>
			<div class="qr-image-wrap">
				<img src="{{ asset('images/coupon-code.gif') }}" alt="クーポンQRコード" />
			</div>
			<p class="qr-hint">スタッフにこの画面を見せてください</p>
		</div>
	</div>
</div>
@endsection
