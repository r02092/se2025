
@extends('layouts.app')

@section('content')
<div class="container">
	<h1>利用者追加</h1>
	@if (session('success'))
		<div class="alert alert-success">
			{{ session('success') }}
		</div>
	@endif
	<form action="{{ route('admin.users.store') }}" method="POST">
		@csrf

		<div class="form-group">
			<label for="login_name">ログインID</label>
			<input type="text" name="login_name" id="login_name" class="form-control" required>
		</div>

		<div class="form-group">
			<label for="password">パスワード</label>
			<input type="password" name="password" id="password" class="form-control" required>
		</div>

		<div class="form-group">
			<label for="name">名前</label>
			<input type="text" name="name" id="name" class="form-control" required>
		</div>

		<div class="form-group">
			<label for="permission">権限</label>
			<select name="permission" id="permission" class="form-control">
				<option value="1">一般</option>
				<option value="0">管理者</option>
				<option value="2">事業者</option>
			</select>
		</div>

		<button type="submit" class="btn btn-primary">登録</button>
	</form>
</div>
@endsection
