@extends('layouts.app')

@section('title', '事業者機能')

@section('content')
<h1>事業者機能</h1>

<div class="general-box column-menu">
	<h2>事業者メニュー</h2>
	<div class="spot-divider" aria-hidden="true"></div>

	<!-- <div class="column-menu"> -->
		<button class="settings-button" onclick="location.href = '{{ route('business.spots') }}'">
			スポット情報編集
		</button>

		<button class="settings-button" onclick="location.href = '{{ route('business.data') }}'">
			観光データ確認
		</button>

		<!-- 以下 遷移先空白 -->
		<button class="settings-button" onclick="location.href = '{{ route('business.api') }}'">
			APIキー管理
		</button>

		<button class="settings-button" onclick="location.href = '{{ route('business.invoice') }}'">
			請求書[PDF]
		</button>
	</div>
</div>
@endsection
