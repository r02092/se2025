<div class="header-bar">
	<div class="title">
		<img src="{{ asset('images/logo_white.svg') }}" width="90" alt="SceneTrip">
	</div>
	<!-- <div class="hamburger">
		<div class="hamburger-bars">
			<div class="bar"></div>
			<div class="bar"></div>
			<div class="bar"></div>
		</div>
	</div> -->
	<div class="header-login">
		@auth
			<a href="{{ route('profile') }}" class="header-profile" title="プロフィールを表示">
				<img src="{{ Auth::user()->icon_ext ? asset('storage/icons/' . Auth::user()->id . '.' . Auth::user()->icon_ext . '?' . time()) : asset('storage/icons/default_icon.jpg') }}" alt="プロフィール">
			</a>
		@else
			<button type="button" onclick="location.href = '{{ route('login') }}'">
				ログイン
			</button>
		@endauth
	</div>
</div>
