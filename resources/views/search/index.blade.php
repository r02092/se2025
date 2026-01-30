@extends('layouts.app')

@section('title', '検索結果')

@section('content')

{{-- ▼▼▼ 修正版: エラー対策済みハイライト関数 ▼▼▼ --}}
@php
	function highlightKeywords($text, $searchQuery) {
		// 1. 検索ワードが配列なら文字列に直す
		if (is_array($searchQuery)) {
			$searchQuery = implode(' ', $searchQuery);
		}

		// 2. 対象テキストがオブジェクト/配列なら文字列を取り出す
		if (is_object($text)) {
			$text = $text->keyword ?? '';
		}
		if (is_array($text)) {
			$text = $text['keyword'] ?? implode(' ', $text);
		}

		// 3. 念のため文字列キャスト（null対策）
		$text = (string)$text;

		if (empty($text) || empty($searchQuery)) {
			return e($text);
		}

		// 4. 分割してハイライト処理
		$rawKeywords = preg_split('/[\s]+/', mb_convert_kana($searchQuery, 's'), -1, PREG_SPLIT_NO_EMPTY);
		if (empty($rawKeywords)) return e($text);

		$patterns = [];
		foreach ($rawKeywords as $word) {
			$word = (string)$word;
			$patterns[] = preg_quote(e($word), '/');
			$patterns[] = preg_quote(e(mb_convert_kana($word, 'C')), '/');
			$patterns[] = preg_quote(e(mb_convert_kana($word, 'c')), '/');
			$patterns[] = preg_quote(e(mb_convert_kana($word, 'KV')), '/');
		}
		$patterns = array_unique($patterns);

		$regex = '/(' . implode('|', $patterns) . ')/iu';

		// 蛍光ペン風スタイル
		return preg_replace(
			$regex,
			'<span style="background:linear-gradient(transparent 60%, #fde047 60%); font-weight:bold;">$1</span>',
			e($text)
		);
	}
@endphp

<div style="max-width: 760px; margin: 0 auto;">

	{{-- ヘッダー --}}
	<div style="margin-bottom: 30px;">
		<h1 style="font-size: 1.5rem; font-weight: bold; color: #333; margin-bottom: 10px;">
			🔍 検索結果
		</h1>
		<p style="color: #666;">
			@if($departure)
				「<strong>{{ $departure }}</strong>」周辺、かつ
			@endif
			{{-- ▼▼▼ 修正箇所: ここで配列エラーが起きていたので対策を追加 ▼▼▼ --}}
			「<strong>{{ is_array($destination) ? implode(' ', $destination) : $destination }}</strong>」を含むスポット
		</p>
	</div>

	{{-- エラー表示 --}}
	@if($departureNotFound)
		<div style="background: #fef2f2; border: 1px solid #fecaca; color: #b91c1c; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
			<strong>⚠️ 出発地が見つかりませんでした</strong><br>
			出発地「{{ $departure }}」の位置情報が取得できませんでした。<br>
			キーワード「{{ is_array($destination) ? implode(' ', $destination) : $destination }}」のみでの検索結果を表示しています。
		</div>
	@endif

	{{-- 検索結果リスト --}}
	<div class="search-results">
		@if(count($spots) > 0)
			@foreach($spots as $spot)
				<div class="general-box result-card">

					{{-- カード全体リンク --}}
					<a href="{{ route('detail', ['id' => $spot->id]) }}" class="result-link">

						{{-- 画像エリア --}}
						<div class="spot-image-div">
							<img src="{{ isset($spot->img_ext) ? asset('storage/spots/' . $spot->id . '.' . $spot->img_ext) : asset('images/no-image.svg') }}"
								 alt="{{ $spot->name }}"
								 class="spot-image">
						</div>

						{{-- 情報エリア --}}
						<div>
							<h2>
								{!! highlightKeywords($spot->name, $destination) !!}
							</h2>
							<p>
								{!! highlightKeywords($spot->description, $destination) !!}
							</p>

							{{-- キーワードタグ --}}
							@if(!empty($spot->keywords))
								{{-- objectタグでリンクの入れ子エラーを回避 --}}
								<object>
									<div style="margin-top: 8px;">
										@foreach($spot->keywords as $keywordObj)
											{{-- ▼▼▼ 修正: $keywordObj->keyword で文字だけを取り出す ▼▼▼ --}}
											<a href="{{ request()->fullUrlWithQuery([
													'destination' => $keywordObj->keyword,
													'keyword' => null,
													'id' => null,
													'ids' => null
												]) }}"
											   style="text-decoration: none; display: inline-block;">
												<span style="background: #f3f4f6; color: #555; padding: 2px 8px; border-radius: 4px; font-size: 0.9rem; margin-right: 5px; margin-bottom: 5px; display: inline-block;">
													{{-- ハイライト関数にも keyword カラムを渡す --}}
													# {!! highlightKeywords($keywordObj->keyword, $destination) !!}
												</span>
											</a>
										@endforeach
									</div>
								</object>
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
	<div class="out-btn btn-bottom">
		<a href="/">
			条件を変えて再検索
		</a>
	</div>

</div>

@endsection
