<!doctype html>
<html lang="ja">
<head>
	<meta charset="UTF-8" />
	<title>@yield('title', 'SceneTrip') - {{ config('app.name', 'Laravel') }}</title>
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<meta name="csrf-token" content="{{ csrf_token() }}">
	@vite(['resources/css/app.css'])
	@stack('scripts')
</head>
<body>
	<div class="container">
		@include('layouts.partials.header')
		@yield('content')
		@include('layouts.partials.bottom-bar')
	</div>
</body>
</html>
