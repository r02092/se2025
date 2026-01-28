@extends('layouts.app')

@section('title', '二要素認証設定')

@section('content')
<div class="main-area">
	<div class="general-box form-container">
		<h1 class="h2">二要素認証設定</h1>
				<div>
					@if (session('success'))
						<div>
							{{ session('success') }}
						</div>
					@endif

					@if ($enabled)
						{{-- ▼ 設定済みの場合 ▼ --}}
						<div class="twofa-center">
							<div class="h5 twofa-fb">二要素認証は有効です</div>
							<p>アカウントのセキュリティは強化されています。</p>

							<div class="general-box divider" aria-hidden="true"></div>

							<form action="{{ route('profile.2fa.destroy') }}" method="POST" onsubmit="return confirm('セキュリティレベルが下がりますが、本当に解除しますか？');">
								@csrf
								<button type="submit" class="btn">
									設定を解除する
								</button>
							</form>
						</div>

					@else
						{{-- ▼ 未設定（これから設定）の場合 ▼ --}}
						<div>
							<div class="twofa-center">
								<h3>Step 1</h3>
								<p>認証アプリで二次元コードを読み取ってください。</p>

								<div>
									{{-- QRコード表示 (SVG) --}}
									{!! $qrImage !!}
								</div>

								<p>読み取れない場合の手動入力キー:</p>
								<code>{{ $secretKey }}</code>
							</div>

							<div class="twofa-center">
								<h3>Step 2</h3>
								<p>アプリに表示された6桁のコードを入力して有効化してください。</p>

								<form action="{{ route('profile.2fa.store') }}" method="POST">
									@csrf
									{{-- 秘密鍵をhiddenで送る --}}
									<input type="hidden" name="secret_key" value="{{ $secretKey }}">

									<div>
										<label for="code" class="form-label">認証コード</label>
										<input type="text" name="one_time_password" id="code"
											class="twofa-center"
											placeholder="000000" maxlength="6" inputmode="numeric" required>
										@error('one_time_password')
											<div class="invalid-feedback">{{ $message }}</div>
										@enderror
									</div>

									<div>
										<button type="submit" class="btn btn-primary">
											有効にする
										</button>
									</div>
								</form>
							</div>
						</div>
					@endif

					<div class="twofa-center kmn-mt">
						<button type="button" class="btn btn-secondary" onclick="location.href='{{ route('profile') }}'">
							プロフィールに戻る
						</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
