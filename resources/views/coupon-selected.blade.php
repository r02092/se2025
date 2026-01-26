@extends('layouts.app')

@section('title', 'クーポン詳細')

@push('scripts')
@vite(['resources/ts/coupon_selected.ts'])
@endpush

@section('content')
<div class="main-area coupon-area-background">
	<div class="coupon-titlebar">
		<h1>クーポン 詳細</h1>
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

	<!-- 下部固定のボタン -->
	<div class="coupon-use-button-area">
		<div
			class="coupon-use-button"
			type="button"
			onclick="document.getElementById('coupon-confirm-overlay').classList.add('show')"
			aria-label="クーポン利用"
		>
			クーポンを利用する
		</div>
		<div class="close-btn" onclick="location.href = '{{ route('coupon') }}'">
			一覧に戻る
		</div>
	</div>

	<!-- 確認オーバーレイ -->
	<div id="coupon-confirm-overlay" class="qr-overlay">
		<div class="qr-modal">
			<h3 class="qr-modal-title">確認</h3>
			<h3 class="qr-modal-sub confirm">本当に使いますか？</h3>
			<div class="qr-modal-actions">
				<a class="qr-modal-close-btn confirm" onclick="location.href='{{ route('coupon.qr', $coupon->id ?? 1) }}'">はい</a>
				<a class="close-btn" onclick="document.getElementById('coupon-confirm-overlay').classList.remove('show')">いいえ</a>
			</div>
		</div>
	</div>
</div>
@endsection
