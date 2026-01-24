@extends('layouts.app')

@section('title', 'ログアウト確認')

@section('content')
<h1>ログアウト確認</h1>
<section class="general-box achievement" aria-labelledby="achievement-title">
	<div id="achievement-title">ログアウトしますか？</div>
	<div style="text-align: center;">「はい」をクリックすると、ログアウトしてトップページに戻ります。<br></div>
	<div class="general-box divider" aria-hidden="true"></div>

	<div style="display: flex; gap: 12px; justify-content: center; margin-top: 20px;">
		<form method="POST" action="{{ route('logout.confirm') }}" style="display: inline;">
			@csrf
			<button type="submit" class="btn btn-logout btn-big">
				はい
			</button>
		</form>
		<button type="button" class="btn btn-secondary btn-big" onclick="location.href = '{{ route('profile') }}'">
			いいえ
		</button>
	</div>
</section>
@endsection
