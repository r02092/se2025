@extends('layouts.app')

@section('title', 'SceneTrip - ユーザー詳細')

@section('content')
<div class="post-titlebar">
	<h1>ユーザー詳細</h1>
	<div class="post-titlebar-update">
		<button type="button" onclick="location.href = '{{ route('post') }}'">
			内容更新
		</button>
	</div>
</div>

<section class="general-box coupon-card" style="margin: 0 5% 16px">
	<img class="coupon-thumb" src="{{ asset('images/Profile_4.jpg') }}" />
	<div class="coupon-info">
		<h2 class="coupon-title">ツル☆ハシ</h2>
		<p class="coupon-desc">
			ID: 4<br />
			ログイン名: tsuruhashi04<br />
			種別: 利用者<br />
			スタンダードプラン: 0<br />
			プレミアムプラン: 0<br />
			住所: 未登録<br />
			二要素認証: 無効
		</p>
	</div>
</section>
<!-- フィード（投稿カードをここに差し込む） -->
<section id="feed" class="feed">
	<!-- post.js がここに投稿カードを挿入します -->
</section>

<!-- 投稿カードのテンプレート（post.js がこれをクローンします） -->
<template id="post-template">
	<article class="general-box post-card">
		<header class="post-head">
			<img class="post-avatar" alt="avatar" />
			<div class="post-meta">
				<div class="post-author"></div>
				<div class="post-time"></div>
			</div>
		</header>
		<img class="post-image" alt="" />
		<div class="post-body"></div>
		<div style="color: #aaa; font-size: 0.6em">
			ID: 3<br />
			座標: <a href="#">(133.685047, 33.607133)</a><br />
			IPアドレス: 192.0.2.2<br />
			User-Agent: Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36
			(KHTML, like Gecko) Chrome/142.0.0.0 Mobile Safari/537.36
		</div>
		<button class="comment-send">
			削除
		</button>
	</article>
</template>
@endsection
