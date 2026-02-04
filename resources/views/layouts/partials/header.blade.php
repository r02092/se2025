<div class="header-bar">
	<div class="title">
		<img src="{{ asset('images/logo_white.svg') }}" width="90" alt="SceneTrip">
	</div>
	{{-- <div class="hamburger">
		<div class="hamburger-bars">
			<div class="bar"></div>
			<div class="bar"></div>
			<div class="bar"></div>
		</div>
	</div> --}}
	<div class="header-login">
		@auth
			<a href="{{ route('profile') }}" class="header-profile" title="プロフィールを表示">
				<img src="{{ Auth::user()->icon_url }}" alt="プロフィール">
			</a>
		@else
			<button type="button" onclick="location.href = '{{ route('login') }}'">
				ログイン/会員登録
			</button>
		@endauth
	</div>
</div>
