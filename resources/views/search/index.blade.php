@extends('layouts.app')

@section('title', '検索結果')

@section('content')

{{-- ▼▼▼ ハイライト用のヘルパー関数 ▼▼▼ --}}
@php
	// ハイライト処理関数
	function highlightKeywords($text, $searchQuery) {
		// オブジェクト対策
		if (is_object($text)) {
			$text = $text->keyword ?? '';
		}
		if (is_array($text)) {
			$text = $text['keyword'] ?? '';
		}

		if (empty($text) || empty($searchQuery)) {
			return e($text);
		}

		// 1. 検索ワードをスペースで分割
		$rawKeywords = preg_split('/[\s]+/', mb_convert_kana($searchQuery, 's'), -1, PREG_SPLIT_NO_EMPTY);

		if (empty($rawKeywords)) {
			return e($text);
		}

		// 2. 表記ゆれパターンの生成
		$patterns = [];
		foreach ($rawKeywords as $word) {
			$eWord = e($word);
			$patterns[] = preg_quote($eWord, '/');
			$patterns[] = preg_quote(e(mb_convert_kana($word, 'C')), '/');
			$patterns[] = preg_quote(e(mb_convert_kana($word, 'c')), '/');
			$patterns[] = preg_quote(e(mb_convert_kana($word, 'KV')), '/');
		}
		$patterns = array_unique($patterns);

		// 3. 正規表現で一括置換
		$regex = '/(' . implode('|', $patterns) . ')/iu';
		return preg_replace(
			$regex,
			'<strong>$1</strong>',
			e($text)
		);
	}
@endphp

<div class="container" style="max-width: 800px; margin: 0 auto; padding: 30px 20px;">

	{{-- ヘッダー --}}
	<div style="margin-bottom: 30px;">
		<h1 style="font-size: 1.5rem; font-weight: bold; color: #333; margin-bottom: 10px;">
			🔍 検索結果
		</h1>
		<p style="color: #666;">
			@if($departure)
				「<strong>{{ $departure }}</strong>」周辺、かつ
			@endif
			「<strong>{{ $destination }}</strong>」を含むスポット
		</p>
	</div>

	{{-- エラー表示 --}}
	@if($departureNotFound)
		<div style="background: #fef2f2; border: 1px solid #fecaca; color: #b91c1c; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
			<strong>⚠️ 出発地が見つかりませんでした</strong><br>
			出発地「{{ $departure }}」の位置情報が取得できませんでした。<br>
			キーワード「{{ $destination }}」のみでの検索結果を表示しています。
		</div>
	@endif

	{{-- 検索結果リスト --}}
	<div class="search-results">
		@if(count($spots) > 0)
			@foreach($spots as $spot)
				<div class="result-card">

					{{-- カード全体をリンクにする --}}
					<a href="{{ route('detail', ['id' => $spot->id]) }}" class="result-link">

						{{-- 1. 画像エリア (CSSクラスで制御) --}}
						<div class="spot-image-div">
							<img src="{{ isset($spot->img_ext) ? ('storage/spots/' . $spot->id . '.' . $spot->img_ext) : asset('images/no-image.png') }}"
								 alt="{{ $spot->name }}"
								 class="spot-image">
						</div>

						{{-- 2. 情報エリア --}}
						<div>

							{{-- タイトル --}}
							<h2>
								{!! highlightKeywords($spot->name, $destination) !!}
							</h2>

							{{-- 説明文 --}}
							<p>
								{!! highlightKeywords($spot->description, $destination) !!}
							</p>

							{{-- キーワードタグ --}}
							@if(!empty($spot->keywords))
								<div>
									@foreach($spot->keywords as $keyword)
										<span>
											# {!! highlightKeywords($keyword, $destination) !!}
										</span>
									@endforeach
								</div>
							@endif
						</div>
					</a>
				</div>
			@endforeach
		@else
			{{-- ヒットなし --}}
			<div style="text-align: center; padding: 60px 20px; background: #f9fafb; border-radius: 12px;">
				<p style="font-size: 4rem; margin-bottom: 20px;">😢</p>
				<h3 style="font-weight: bold; color: #333; margin-bottom: 10px;">見つかりませんでした</h3>
				<p style="color: #666;">
					キーワードを変えて、もう一度検索してみてください。
				</p>
				<div style="margin-top: 30px;">
					<a href="/" style="color: #108a66; font-weight: bold; text-decoration: underline;">ホームに戻る</a>
				</div>
			</div>
		@endif
	</div>

	{{-- 再検索ボタン --}}
	<div style="margin-top: 40px; text-align: center;">
		<a href="/" style="display: inline-block; background: #fff; border: 1px solid #ccc; color: #333; padding: 12px 30px; border-radius: 30px; text-decoration: none; font-weight: bold; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
			条件を変えて再検索
		</a>
	</div>

</div>

@endsection
