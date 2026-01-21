@extends('layouts.app')

@section('title', 'プロフィール編集')

@section('content')
<div class="main-area">
	<div class="general-box profile-edit-container">
		<h2>プロフィール編集</h2>
		<form method="POST" action="{{ route('profile.update') }}">
			@csrf
			@method('PUT')

			<div class="profile-edit-avatar-group">
				<img
					src="{{ asset('images/Profile_pic.JPG') }}"
					alt="ユーザーの現在のアバター画像"
					class="profile-avatar"
				/>
				<input
					type="file"
					accept="image/png, image/jpeg"
					id="avatar-img"
					name="avatar-img"
				/>
				<label for="avatar-img">画像をアップロード</label>
			</div>

			<label for="name">表示名</label>
			<input
				type="text"
				id="name"
				name="name"
				required
				value="{{ old('name', Auth::user()->name) }}"
			/>

			<label for="current-password">現在のパスワード</label>
			<input
				type="password"
				id="current-password"
				name="current-password"
			/>

			<label for="new-password">新しいパスワード</label>
			<input
				type="password"
				id="new-password"
				name="new-password"
			/>

			<label for="confirm-password">新しいパスワード(確認用)</label>
			<input
				type="password"
				id="confirm-password"
				name="confirm-password"
			/>

			<button type="button" onclick="location.href='{{ route('profile.2fa') }}'">二要素認証を設定</button>
			<div class="profile-edit-button-group">
				<button
					class="profile-edit-button-cancel"
					type="button"
					onclick="location.href = '{{ route('profile') }}'"
				>
					キャンセル
				</button>
				<button type="submit">
					保存
				</button>
			</div>
		</form>
	</div>
</div>

<!-- アカウント削除項目 -->
@endsection
