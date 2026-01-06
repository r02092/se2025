@extends('layouts.app')

@section('title', 'アカウント作成')

@section('content')
<div class="main-area">
	<div class="general-box form-container">
		<h2>アカウント作成</h2>
		<form method="POST">
			@csrf
			<label for="name">表示名</label>
			<input type="text" id="name" name="name" required />

			<label for="username">ログイン名</label>
			<input type="text" id="username" name="username" required />

			<label for="password">パスワード</label>
			<input type="password" id="password" name="password" required />

			<label for="password_confirm">パスワード（確認用）</label>
			<input
				type="password"
				id="password_confirm"
				name="password_confirm"
				required
			/>

			<button type="submit">アカウント作成</button>
		</form>
		<div class="h5">既にアカウントをお持ちの方は <a href="login.html">こちら</a></div>
	</div>
</div>
@endsection
