@extends('layouts.app')

@section('title', '新規投稿')

@push('scripts')
@vite(['resources/ts/photo_form.ts'])
@endpush

@section('content')
<h1>新規投稿</h1>
<div class="general-box form-container">
	<form method="POST">
		@csrf

		<label for="photo" class="spot-detail-form-label">写真</label>
		<img id="photo_preview">
		<input type="file" id="photo" class="photo-file spot-detail-form-group" name="photo" accept="image/*">
		<div class="spot-detail-form-group">
			<label for="comment" class="spot-detail-form-label">コメント</label>
			<textarea name="comment" id="comment" rows="4" class="spot-detail-form-input" placeholder="写真の説明など、写真に関してコメントを書きましょう" required></textarea>
		</div>
		<label class="spot-detail-form-label">位置</label>
		<input type="hidden" name="coord">
		<div class="map-area spot-detail-form-group">
			<div id="map"></div>
		</div>
		<button type="button" id="location_btn" class="btn btn-primary btn-big spot-detail-btn spot-detail-form-group">
			現在地を入力
		</button>

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
</div>
@endsection
