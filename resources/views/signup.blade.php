@extends('layouts.app')

@section('title', 'アカウント作成')

@section('content')
<div class="main-area">
	<div class="general-box form-container">
		<h2>アカウント作成</h2>
		<form>
			<label for="username">ユーザー名</label>
			<input type="text" id="username" name="username" required />

			<label for="email">ログイン名</label>
			<input type="email" id="email" name="email" required />

			<label for="password">パスワード</label>
			<input type="password" id="password" name="password" required />

			<label for="confirm-password">パスワード（確認用）</label>
			<input
				type="password"
				id="confirm-password"
				name="confirm-password"
				required
			/>

			<button type="submit">アカウント作成</button>
		</form>
		<h5>既にアカウントをお持ちの方は <a href="login.html">こちら</a></h5>
	</div>
</div>
@endsection
