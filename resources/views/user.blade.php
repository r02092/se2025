@extends('layouts.app')

@section('title', 'ユーザー一覧')

@section('content')
<h1>ユーザー一覧</h1>
<section class="coupon-list">
	<article class="general-box coupon-card" tabindex="0" role="article">
		<img class="coupon-thumb" src="{{ asset('images/Profile_pic.JPG') }}" />
		<div class="coupon-info">
			<h2 class="coupon-title">Share</h2>
			<p class="coupon-desc">
				ID: 1<br />
				ログイン名: share_admin<br />
				種別: 管理者<br />
			</p>
		</div>
	</article>
	<article class="general-box coupon-card" tabindex="0" role="article">
		<img class="coupon-thumb" src="{{ asset('images/Harimaya_Bridge.jpg') }}" />
		<div class="coupon-info">
			<h2 class="coupon-title">はりまや</h2>
			<p class="coupon-desc">
				ID: 2<br />
				ログイン名: yabashi_harimaaaa<br />
				種別: 利用者<br />
			</p>
		</div>
	</article>
	<article class="general-box coupon-card" tabindex="0" role="article">
		<img class="coupon-thumb" src="{{ asset('images/Profile_3.jpg') }}" />
		<div class="coupon-info">
			<h2 class="coupon-title">中華そば 碧空</h2>
			<p class="coupon-desc">
				ID: 3<br />
				ログイン名: hekiku_ramen<br />
				種別: 承認済み事業者<br />
			</p>
		</div>
	</article>
	<article class="general-box coupon-card" tabindex="0" role="article">
		<img class="coupon-thumb" src="{{ asset('images/Profile_4.jpg') }}" />
		<div class="coupon-info">
			<h2 class="coupon-title">ツル☆ハシ</h2>
			<p class="coupon-desc">
				ID: 4<br />
				ログイン名: tsuruhashi04<br />
				種別: 利用者<br />
			</p>
		</div>
	</article>
	<article class="general-box coupon-card" tabindex="0" role="article">
		<img class="coupon-thumb" src="{{ asset('images/Profile_5.jpg') }}" />
		<div class="coupon-info">
			<h2 class="coupon-title">柳 勇樹</h2>
			<p class="coupon-desc">
				ID: 5<br />
				ログイン名: yukiyanagi<br />
				種別: 利用者<br />
			</p>
		</div>
	</article>
</section>
@endsection
