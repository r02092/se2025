@extends('layouts.app')

@section('title', '検索結果 - SceneTrip')

@section('content')
<div class="general-box" style="padding: 20px; margin: 20px 5%;">
	<h2 style="font-size:1.5rem; font-weight:bold; color:#16a34a; margin-bottom:20px;">検索結果</h2>

	{{-- 検索条件の表示 --}}
	<div class="bg-white shadow rounded-lg p-6 mb-6" style="background:#fff; padding:20px; border-radius:8px; box-shadow:0 2px 5px rgba(0,0,0,0.1);">
		<p style="margin-bottom:10px;">
			<span style="font-weight:bold; color:#555;">出発地:</span> {{ $departure ?: '指定なし' }}
		</p>
		<p style="margin-bottom:0;">
			<span style="font-weight:bold; color:#555;">目的地:</span> <span style="font-size:1.2rem; font-weight:bold;">{{ $destination }}</span>
		</p>
	</div>

	{{-- 出発地が見つからなかった場合のエラー表示 --}}
	@if(isset($departureNotFound) && $departureNotFound)
		<div style="padding:15px; background:#fff3cd; color:#856404; border-radius:8px; margin-bottom:20px; border:1px solid #ffeeba;">
			<strong>注意:</strong> 出発地「{{ $departure }}」に一致するスポットが見つかりませんでした。<br>
			<span style="font-size:0.9rem;">※ 正しい名称を入力するか、現在地を利用してください。</span>
		</div>
	@endif

	{{-- 検索結果リスト --}}
	@if(isset($spots) && count($spots) > 0)
		<h3 style="font-size:1.1rem; font-weight:bold; margin-bottom:10px;">見つかったスポット</h3>

		<ul style="list-style:none; padding:0;">
			@foreach($spots as $spot)
				<li style="background:#fff; padding:15px; margin-bottom:15px; border-radius:8px; border:1px solid #eee; display:flex; gap:15px;">
					{{-- 画像表示 --}}
					<img src="{{ asset($spot->image_path ?? 'images/no_image.jpg') }}"
						 alt="{{ $spot->name }}"
						 style="width:100px; height:100px; object-fit:cover; border-radius:8px; background:#eee;"
						 onerror="this.src='https://placehold.jp/150x150.png?text=No+Image'">

					<div>
						<h4 style="font-weight:bold; font-size:1.1rem; color:#16a34a;">{{ $spot->name }}</h4>
						<p style="font-size:0.9rem; color:#666; margin:5px 0;">{{ $spot->description ?? '詳細なし' }}</p>

						{{-- ルート案内へのリンク例（機能はまだですがボタンだけ置いておけます） --}}
						<a href="#" style="color:#16a34a; text-decoration:underline; font-size:0.9rem;">ルート案内を開始する</a>
					</div>
				</li>
			@endforeach
		</ul>
	@else
		{{-- 見つからなかった場合 --}}
		<div style="padding:20px; background:#fff3cd; color:#856404; border-radius:8px;">
			<p>「{{ $destination }}」に一致するスポットが見つかりませんでした。</p>
			<a href="/" style="color:#856404; text-decoration:underline; font-weight:bold;">ホームに戻って再検索</a>
		</div>
	@endif

	<div style="margin-top:20px;">
		<a href="/" style="color:#16a34a; text-decoration:underline;">← ホームに戻る</a>
	</div>
</div>
@endsection
