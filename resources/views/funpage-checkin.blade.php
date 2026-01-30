@extends('layouts.app')

@section('title', 'チェックイン')

@push('scripts')
@vite(['resources/ts/funpage_checkin.ts'])
@endpush

@section('content')
<div class="main-area">
	<h1>チェックイン</h1>

	<div id="checkin-qr-overlay" class="general-box scanner-container checkin-qr-overlay">
		<p class="checkin-qr-sub">
			設置されている二次元コードを読み取ってください。
		</p>
		{{-- 1. カメラ映像を表示するビデオタグを追加 --}}
		<div class="video-wrapper">
			<video id="qr-video"></video>
			{{-- スキャン範囲を示すガイド枠（任意） --}}
			<div class="scan-guide"></div>
		</div>
		<p class="checkin-qr-hint">
			三隅の四角がはっきり映るようにしてください。
		</p>

		<p id="scanner-status">カメラを起動中……</p>

		<div class="button-group">
			<button type="button" class="btn btn-secondary" onclick="location.href='{{ route('funpage') }}'">
				戻る
			</button>
		</div>
	</div>
</div>

{{-- 2. 内部設計書の「qr-scanner」を制御するJSを読み込み --}}
@vite(['resources/ts/funpage_checkin.ts'])
@endsection
