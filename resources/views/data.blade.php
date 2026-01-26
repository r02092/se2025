@extends('layouts.app')

@section('title', '観光データ')

@section('content')
<h1>観光データ</h1>

<div class="general-box ai-suggest">
	<h1 class="h2">人気のスポット</h1>
	<div class="spot-divider" aria-hidden="true"></div>

	<ul class="spot-list" aria-label="人気のスポット一覧">
		<li class="spot-item">
			<img
				class="spot-thumb"
				src="{{ asset('images/Harimaya_Bridge.jpg') }}"
				alt="はりまや橋"
			>
			<div class="spot-content">
				<h2 class="spot-title">はりまや橋</h2>
				<div class="spot-desc">過去1週間のスタンプ数: 2</div>
			</div>
		</li>
		<li class="spot-item">
			<img class="spot-thumb" src="{{ asset('images/post-station.jpg') }}" alt="はりまや橋">
			<div class="spot-content">
				<h2 class="spot-title">土佐山田駅</h2>
				<div class="spot-desc">過去1週間のスタンプ数: 1</div>
			</div>
		</li>
		<li class="spot-item">
			<img class="spot-thumb" src="{{ asset('images/ryugado.jpg') }}" alt="はりまや橋">
			<div class="spot-content">
				<h2 class="spot-title">龍河洞</h2>
				<div class="spot-desc">過去1週間のスタンプ数: 1</div>
			</div>
		</li>
	</ul>
</div>
<div class="general-box ai-suggest" style="margin-top: 24px">
	<h2>人気の口コミが多い場所</h2>
	<div class="spot-divider" aria-hidden="true"></div>

	<ul class="spot-list" aria-label="人気のスポット一覧">
		<li class="spot-item">
			<img class="spot-thumb" src="{{ asset('images/post-cafe.jpg') }}" alt="はりまや橋">
			<div class="spot-content">
				<h2 class="spot-title">高知工科大学 香美食堂</h2>
				<div class="spot-desc">口コミの閲覧合計数: 24</div>
			</div>
		</li>
		<li class="spot-item">
			<img class="spot-thumb" src="{{ asset('images/ryugado.jpg') }}" alt="はりまや橋">
			<div class="spot-content">
				<h2 class="spot-title">龍河洞</h3>
				<div class="spot-desc">口コミの閲覧合計数: 18</div>
			</div>
		</li>
	</ul>
</div>
<div class="suggest"></div>
@endsection
