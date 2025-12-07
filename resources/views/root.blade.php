<!DOCTYPE html>
<html lang="{{str_replace('_', '-', app()->getLocale())}}">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>{{config('app.name', 'Laravel')}}</title>
		@vite(['resources/css/app.css', 'resources/ts/app.ts'])
	</head>
	<body>
		<h1>{{config('app.name', 'Laravel')}}</h1>
		テスト
	</body>
</html>
