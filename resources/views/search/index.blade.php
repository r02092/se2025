@extends('layouts.app')

@section('title', '検索結果 - SceneTrip')

@section('content')
<div class="general-box" style="padding: 20px; margin: 20px 5%;">
	<h1 style="font-size:1.5rem; color:#16a34a; margin-bottom:20px;">検索結果</h1>

	{{-- ▼▼▼ 修正: 検索条件（キーワードのみ表示） ▼▼▼ --}}
	<div class="bg-white shadow rounded-lg p-6 mb-6" style="background:#fff; padding:20px; border-radius:8px; box-shadow:0 2px 5px rgba(0,0,0,0.1);">
		<p style="margin-bottom:0; display:flex; align-items:center; gap:10px;">
			<span style="font-weight:bold; color:#555;">検索キーワード:</span>
			<span style="font-size:1.3rem; font-weight:bold; color:#333;">
				{{-- 出発地と目的地の両方が入力されている場合も考慮して並べて表示（片方なら片方のみ表示） --}}
				{{ $destination }}
				@if(!empty($departure))
					<span style="font-size:1rem; color:#888; font-weight:normal;">( {{ $departure }} )</span>
				@endif
			</span>
		</p>
	</div>

	{{-- 出発地エラーブロックはキーワード検索の文脈に合わないため削除しました --}}

	{{-- 検索結果リスト --}}
	@if(isset($spots) && count($spots) > 0)
		<h3 style="font-size:1.1rem; font-weight:bold; margin-bottom:10px;">見つかったスポット</h3>

		<ul style="list-style:none; padding:0;">
			@foreach($spots as $spot)
				<li style="background:#fff; padding:15px; margin-bottom:15px; border-radius:8px; border:1px solid #eee; display:flex; gap:15px;">
					{{-- 画像表示 --}}
					<img src="{{ $spot->image_url ?? asset('images/no_image.jpg') }}"
						 alt="{{ $spot->name }}"
						 style="width:100px; height:100px; object-fit:cover; border-radius:8px; background:#eee; flex-shrink: 0;"
						 onerror="this.src='https://placehold.jp/150x150.png?text=No+Image'">

					<div style="flex-grow: 1;">
						<h4 style="font-weight:bold; font-size:1.1rem; color:#16a34a; margin-top:0;">{{ $spot->name }}</h4>
						<p style="font-size:0.9rem; color:#666; margin:5px 0; line-height: 1.5;">{{ $spot->description ?? '詳細なし' }}</p>

						{{-- 詳細画面へのリンク --}}
						<div style="margin-top: 10px; text-align: right;">
							<a href="{{ route('detail', ['id' => $spot->id]) }}"
							   style="color:#16a34a; text-decoration:none; font-weight:bold; border: 1px solid #16a34a; padding: 5px 15px; border-radius: 4px; display: inline-block;">
								詳細を見る
							</a>
						</div>
					</div>
				</li>
			@endforeach
		</ul>
	@else
		{{-- ▼▼▼ 修正: 見つからなかった場合の文言 ▼▼▼ --}}
		<div style="padding:20px; background:#fff3cd; color:#856404; border-radius:8px; text-align: center;">
			<p style="margin-bottom: 15px; font-weight: bold;">該当するスポットが見つかりませんでした。</p>
			<p style="font-size: 0.9rem; margin-bottom: 15px;">キーワードを変えて、もう一度検索してみてください。</p>
			<a href="/" style="color:#856404; text-decoration:underline; font-weight:bold;">ホームに戻って再検索</a>
		</div>
	@endif

	<div style="margin-top:20px;">
		<a href="/" style="color:#16a34a; text-decoration:underline;">← ホームに戻る</a>
	</div>
</div>
@endsection
