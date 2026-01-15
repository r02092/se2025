<!doctype html>
<html lang="ja">
<head>
	<meta charset="UTF-8" />
	<title>@yield('title', 'SceneTrip')</title>
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	@vite(['resources/css/app.css', 'resources/ts/app.ts'])
</head>
<body>
	<div class="container">
		@include('layouts.partials.header')

		@yield('content')

		@include('layouts.partials.bottom-bar')


	</div>

	@yield('scripts')
	@stack('scripts')
</body>
</html>
