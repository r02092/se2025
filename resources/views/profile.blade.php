@extends('layouts.app')

@section('title', 'プロフィール')

@section('content')
<div class="general-box profile-container">
	<h1 class="h2">プロフィール</h1>
	<div class="profile-avatar-group">
		<span>現在のアバター画像</span>
		<img src="{{ Auth::user()->icon_ext ? asset('storage/icons/' . Auth::user()->id . '.' . Auth::user()->icon_ext . '?' . time()) : asset('storage/icons/default_icon.jpg') }}"
			 alt="ユーザーのアバター画像"
			 class="profile-avatar">
	</div>
	<div class="profile-box">
		<dl class="profile">
			<dt>表示名</dt>
			<dd>{{ Auth::user()->name }}</dd>

			<dt>ログイン名</dt>
			<dd>{{ Auth::user()->login_name }}</dd>

			<dt>パスワード</dt>
			<dd>********</dd>

			<dt>二要素認証</dt>
			<dd>{{ Auth::user()->totp_secret ? '設定済み' : '未設定' }}</dd>
		</dl>
	</div>
	<button class="profile-button-edit" onclick="location.href = '{{ route('profile.edit') }}'">
		プロフィールを編集
	</button>

	@if (Auth::user()->permission == \App\Models\User::PERMISSION_BUSINESS)
		<button class="profile-button-business" onclick="location.href = '{{ route('business') }}'">
			事業者画面へ
		</button>
	@elseif (Auth::user()->permission == \App\Models\User::PERMISSION_ADMIN)
		<button class="profile-button-admin" onclick="location.href = '{{ route('admin') }}'">
			管理画面へ
		</button>
	@endif
</div>

<div class="general-box profile-subscription-container">
	<h3>事業者申込</h3>
	<button class="profile-button-subscription" onclick="location.href = '{{ route('subscription.form') }}'">
		登録
	</button>
</div>
<div class="general-box profile-subscription-container">
	<h3>  </h3>
	<button class="profile-button-subscription" style="background-color: red;" onclick="location.href = '{{ route('logout') }}'">
		ログアウト
	</button>
</div>
@endsection
