@extends('layouts.app')

@section('title', 'SceneTrip - APIキー')

@section('content')
<h1>APIキー</h1>
<div class="general-box form-container" style="margin: 0 auto 20px">
	<h2>発行</h2>
	<form>
		<label for="username">名前</label>
		<input type="text" required />
		<button type="submit">発行</button>
	</form>
</div>
<section class="coupon-list">
	<article class="general-box coupon-card" tabindex="0" role="article">
		<div class="coupon-info">
			<h2 class="coupon-title">社内システム用</h2>
			<span class="btn btn-primary">削除</span>
		</div>
	</article>
	<article class="general-box coupon-card" tabindex="0" role="article">
		<div class="coupon-info">
			<h2 class="coupon-title">開発用</h2>
			<span class="btn btn-primary">削除</span>
		</div>
	</article>
</section>
@endsection
