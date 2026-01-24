@extends('layouts.app')

@section('title', 'AIトラベルプランニング')

@push('scripts')
@vite(['resources/ts/ai_plan.ts'])
@endpush

@section('content')
<div class="container" style="max-width: 800px; margin: 0 auto; padding: 40px 20px;">

	{{-- ヘッダーエリア --}}
	<div style="text-align: center; margin-bottom: 40px;">
		<h1 style="font-size: 1.5rem; font-weight: bold; color: #333; margin-bottom: 10px;">
			AIトラベルプランナー
		</h1>
		<div style="display: flex; justify-content: center; align-items: center; gap: 10px; color: #666;">
			@if($depName)
				<span id="ai-from" class="ai-header-spot">{{ $depName }}</span>
			@endif

			@if($depName && $dstName)
				<span>➜</span>
			@else
				<span style="font-size: 0.9rem;">（周辺）</span>
			@endif

			@if($dstName)
				<span id="ai-to" class="ai-header-spot">{{ $dstName }}</span>
			@endif
		</div>
	</div>

	{{-- ▼▼▼ 修正: エラー判定を「両方とも空の場合」に変更 ▼▼▼ --}}
	@if(!$fromSpot && !$toSpot)
		<div style="background: #fef2f2; border: 1px solid #fecaca; color: #b91c1c; padding: 20px; border-radius: 8px;">
			<p style="font-weight: bold; margin-bottom: 10px;">⚠️ スポットが見つかりませんでした</p>
			<p>入力された名前のスポットがデータベースに見つかりませんでした。</p>
			<p style="font-size:0.9rem; margin-top:5px;">※出発地か目的地のどちらか一方は必ず正しく入力してください。</p>
			<div style="margin-top: 20px; text-align: center;">
				<a href="/" style="color: #b91c1c; text-decoration: underline;">ホームに戻る</a>
			</div>
		</div>

	{{-- ▼▼▼ 正常系: AI処理エリア ▼▼▼ --}}
	@else
		<div id="ai-container" data-from="{{ Js::from($fromSpot ? $fromSpot->id : null) }}" data-to="{{ Js::from($toSpot ? $toSpot->id : null) }}">

			{{-- 1. ローディング画面 --}}
			<div id="ai-loading">
				<div class="spinner"></div>
				<h3 style="font-size: 1.2rem; font-weight: bold; color: #333; margin-bottom: 10px;">AIが分析中...</h3>
				<p style="color: #666; font-size: 0.9rem;">
					@if($fromSpot && $toSpot)
						ルート沿いの寄り道スポットを探しています
					@else
						周辺のおすすめスポットを探しています
					@endif
					<br>
					<span style="font-size: 0.8rem; color: #999;">（これには数秒〜数十秒かかる場合があります）</span>
				</p>
			</div>

			{{-- 2. 結果表示エリア --}}
			<div id="ai-result" style="display: none;">
				<div style="background: linear-gradient(to right, #2563eb, #7c3aed); color: white; padding: 15px 20px;">
					<h2 style="font-size: 1rem; font-weight: bold; margin: 0;">🤖 AIからの提案</h2>
				</div>

				<div class="ai-result-main">
					{{-- 解説テキスト --}}
					<div id="ai-text" style="line-height: 1.8; color: #333; margin-bottom: 30px; font-size: 1rem;">
					</div>

					{{-- スポットリスト --}}
					<h3>
						提案されたスポット
					</h3>
					<div id="ai-spots-list" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 20px;">
					</div>
					<h3>
						スポットを通るルートの例
					</h3>
					<iframe></iframe>
				</div>

				<div style="background: #f9fafb; padding: 15px; text-align: center; border-top: 1px solid #eee;">
					<a href="/" style="color: #2563eb; font-weight: bold; text-decoration: none;">他のルートを探す</a>
				</div>
			</div>

			{{-- 3. エラー表示エリア --}}
			<div id="ai-error" style="display: none; padding: 40px; text-align: center; color: #b91c1c;">
				<p style="font-weight: bold; font-size: 1.1rem;">エラーが発生しました</p>
				<p id="ai-error-msg" style="margin-top: 10px;"></p>
				<a href="/" style="display: inline-block; margin-top: 20px; color: #666; text-decoration: underline;">ホームに戻る</a>
			</div>
		</div>
	@endif
</div>

@endsection
