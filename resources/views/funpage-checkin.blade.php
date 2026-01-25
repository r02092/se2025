@extends('layouts.app')

@section('title', 'チェックイン')

@push('scripts')
@vite(['resources/ts/funpage_checkin.ts'])
@endpush

@section('content')
<div class="main-area">
	<h1>チェックイン</h1>
	<!-- チェックインボックスに data-action を追加 -->
		<div
			class="checkin-qr-modal "
			data-action="checkin"
		>
			<p class="checkin-qr-sub">
				設置されている二次元コードを読み取ってください。
			</p>
			<div class="checkin-qr-image">
				<img src="DSC_8597_1.jpg" alt="SceneTrip QRコード">
			</div>
			<p class="checkin-qr-hint">
				三隅の四角がはっきり映るようにしてください。
			</p>
		</div>

</div>
@endsection
