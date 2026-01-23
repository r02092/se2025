@extends('layouts.app')

@section('title', 'アカウント作成')

@section('content')
<div class="main-area">
	<div class="general-box form-container">
		<h2>アカウント作成</h2>
		<form method="POST" action="{{ route('signup.post') }}">
			@csrf

			<label for="name">表示名<span class="form-detail">（1文字以上255文字以下の文字列）</span></label>
			<input type="text" id="name" name="name" required />
			@error('name')
				<div style="color: red;">{{ $message }}</div>
			@enderror

			<label for="username">ログイン名<span class="form-detail">（半角英数字およびアンダーバーから構成される1文字以上255文字以下の文字列）</span></label>
			<input type="text" id="username" name="username" required />
			@error('username')
				<div style="color: red;">{{ $message }}</div>
			@enderror


			@error('username')
				<p style="color: red; font-size: 0.9em; margin-top: 5px;">{{ $message }}</p>
			@enderror

			<label for="password">パスワード<span class="form-detail">（8文字以上999文字以下の文字列）</span></label>
			<input type="password" id="password" name="password" required />
			@error('password')
				<div style="color: red;">{{ $message }}</div>
			@enderror

			<label for="password_confirmation">パスワード（確認用）</label>
			<input
				type="password"
				id="password_confirmation"
				name="password_confirmation"
				required
			/>

			<div class="terms-check" style="margin: 20px 0;">
				<label style="display: flex; align-items: center; cursor: pointer;">
					<input type="checkbox" name="agree_terms" required style="width: auto; margin-right: 10px;">
					<span>
						<a href="{{ route('terms') }}" target="_blank" style="text-decoration: underline; color: #007bff;">利用規約</a>
						に同意して登録する
					</span>
				</label>
			</div>

			<button type="submit">アカウント作成</button>
		</form>
		<div class="h5">既にアカウントをお持ちの方は <a href="login">こちら</a></div>
	</div>
</div>
@endsection
