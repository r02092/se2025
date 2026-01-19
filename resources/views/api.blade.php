@extends('layouts.app')

@section('title', 'SceneTrip - APIキー')

@section('content')
<h1>APIキー</h1>
<div class="general-box form-container" style="margin: 0 auto 20px">
	<h2>発行</h2>
	<form method="POST">
		@csrf
		<label for="create_name">名前</label>
		<input type="text" name="create_name" required />
		<button type="submit">発行</button>
	</form>
</div>
<section class="coupon-list">
	@foreach($apiKeys ?? [] as $apiKey)
	<article class="general-box coupon-card" tabindex="0" role="article">
		<div class="coupon-info">
			<h2 class="coupon-title">{{ $apiKey->name }}</h2>
			<form method="POST">
				@csrf
				<input type="hidden" name="delete_id" value="{{ $apiKey->id }}">
				<button type="submit" class="comment-send" style="background: #f22727; margin-top: 16px"
						onclick="return confirm('このAPIキーを削除しますか?')">
					削除
				</button>
			</form>
			@if (isset($apiKeyId) && $apiKeyId === $apiKey->id)
			<span class="apikey">APIキー（一度しか表示されません）: <input value="{{ $apiKeyString }}"></span>
			@endif
		</div>
	</article>
	@endforeach
</section>
@endsection
