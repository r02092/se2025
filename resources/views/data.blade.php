@extends('layouts.app')

@section('title', '観光データ')

@section('content')
<h1>観光データ</h1>
@foreach ($data as $spots)
<div class="general-box ai-suggest">
	<h2>人気の{{ $spots[0] }}</h2>
	<div class="spot-divider" aria-hidden="true"></div>

	<div class="spot-list" aria-label="人気のスポット一覧">
		@foreach ($spots[2] as $spot)
		<a class="spot-item" href="{{ route('detail', ['id' => $spot->spot->id]) }}">
			<img
				class="spot-thumb"
				src="{{ isset($spot->spot->img_ext) ? asset('storage/spots/' . $spot->spot->id . '.' . $spot->spot->img_ext) : asset('images/no-image.png') }}"
				alt="{{ $spot->spot->name }}"
			>
			<div class="spot-content">
				<h3 class="spot-title">{{ $spot->spot->name }}</h3>
				<div class="spot-desc">{{ $spots[1] }}数: {{ $spot->count }}</div>
			</div>
		</a>
		@endforeach
	</div>
</div>
@endforeach
@endsection
