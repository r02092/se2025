@extends('layouts.app')

@section('title', 'SceneTrip - 管理者機能')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/home.css') }}" />
@endpush

@section('content')
<h1>管理者機能</h1>

<div class="general-box column-menu">
    <h2>管理メニュー</h2>
    <div class="spot-divider" aria-hidden="true"></div>

	<!-- <div class="column-menu"> -->
		<button class="settings-button" onclick="location.href = '{{ route('admin.users') }}'">
			ユーザー一覧
		</button>

		<button class="settings-button" onclick="location.href = '{{ route('admin.ugc') }}'">
			UGC監視・管理
		</button>

		<button class="settings-button" onclick="location.href = '{{ route('admin.spots') }}'">
			スポット情報編集
		</button>

		<button class="settings-button" onclick="location.href = '{{ route('admin.data') }}'">
			観光データ確認
		</button>
	</div>
</div>
@endsection
