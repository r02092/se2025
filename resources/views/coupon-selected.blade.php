@extends('layouts.app')

@section('title', $coupon->name)

@push('scripts')
@vite(['resources/ts/coupon_selected.ts'])
@endpush

@section('content')
<div class="main-area coupon-area-background">
	<div class="coupon-titlebar">
		<h1>クーポン詳細</h1>
	</div>

	{{-- HERO（上部の大きな画像） --}}
	<section class="hero">
		<img src="{{ isset($coupon->spot->img_ext) ? asset('storage/spots/' . $coupon->spot->id . '.' . $coupon->spot->img_ext) : asset('images/no-image.png') }}" alt="{{ $coupon->spot->name }}" class="hero-img">
	</section>

	{{-- クーポン詳細（背景に直接描画するスタイル） --}}
	<article class="coupon-detail">
		<h2 class="coupon-detail-title">{{ $coupon->name }}</h2>
		@if ($coupon->cond_spot_id)
		<p class="coupon-detail-desc">
			<span>クーポン利用条件</span>: {{ $condSpotName }}にチェックインする
		</p>
		@endif
		<div class="coupon-detail-meta">
			<span class="coupon-tag">{{ $type }}</span>
			<span class="coupon-exp">{{ $coupon->expires_date ? '〜' . $coupon->expires_date : '' }}</span>
		</div>
		{{-- 必要に応じて追記する本文行など --}}
		<p class="coupon-more">
			このクーポンはウェブ限定の割引です。提示はスタッフに画面を見せて確認してください。
		</p>
	</article>

	{{-- 下部固定のボタン --}}
	<div class="coupon-use-button-area">
		@if ($available)
		<div
			class="coupon-use-button"
			type="button"
			onclick="document.getElementById('coupon_confirm_overlay').style.display='flex'"'
			aria-label="クーポン利用"
		>
			クーポンを利用する
		</div>
		@else
		<div class="h3 twofa-center">クーポンの利用条件を満たしていません。</div>
		@endif
		<div class="close-btn" onclick="location.href = '{{ route('coupon') }}'">
			一覧に戻る
		</div>
	</div>

	{{-- 確認オーバーレイ --}}
	<div id="coupon_confirm_overlay" class="qr-overlay" style="display:none">
		<div class="qr-modal">
			<h3 class="qr-modal-title">確認</h3>
			<div class="h3 qr-modal-sub confirm">本当に使いますか？</div>
			<div class="qr-modal-actions">
				<button id="yes_btn" class="btn qr-modal-close-btn confirm" data-active="{{ $active }}">はい</button>
				<button class="btn close-btn" onclick="document.getElementById('coupon_confirm_overlay').style.display='none'">いいえ</button>
			</div>
		</div>
	</div>
</div>
<div id="coupon_overlay" class="qr-overlay" aria-hidden="true" style="display:none">
	<div
		class="qr-modal"
		role="dialog"
		aria-modal="true"
		aria-labelledby="qr-modal-title"
	>
		<h2 class="qr-modal-title">{{ $coupon->name }}</h2>
		<p class="qr-modal-sub">
			こちらの二次元コードをスタッフにご提示ください
		</p>
		<div class="qr-modal-image">
			<img id="coupon_qr"
				alt="クーポン二次元コード"
			>
		</div>
		<div class="qr-modal-actions">
			<a class="qr-modal-close-btn" href="{{ route('coupon') }}">閉じる</a>
		</div>
	</div>
</div>
@endsection
