@extends('layouts.app')

@section('title', '二要素認証')

@section('content')
<div class="main-area">
	<div class="general-box form-container">
		<h1 class="h2">二要素認証</h1>
		<div>
			<p>
				認証アプリ（Google Authenticator等）に表示されている6桁のコードを入力してください。
			</p>

			<form action="{{ route('2fa.verify') }}" method="POST">
				@csrf

				<div>
					<label for="one_time_password">認証コード</label>
					<input type="text"
						id="one_time_password"
						name="one_time_password"
						class="letter-spacing"
						placeholder="000000"
						maxlength="6"
						inputmode="numeric"
						autofocus
						required>

					@error('one_time_password')
						<div class="twofa-center">
							{{ $message }}
						</div>
					@enderror
				</div>

				<div>
					<button type="submit" class="btn btn-primary">認証する</button>
				</div>
			</form>

			<div class="general-box divider" aria-hidden="true"></div>
			<div class="twofa-center">
				<button type="button" class="btn btn-secondary" onclick="location.href='{{ route('profile') }}'">
					キャンセル
				</button>
			</div>
		</div>
	</div>
</div>
@endsection
