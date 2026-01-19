@extends('layouts.app')

@section('title', '事業者申込確定')

@section('content')
<div class="main-area">
	<div class="general-box subscription-confirm-container">
		<h2>事業者申込確定</h2>
		<p>事業者申込を確定しました。</p>
		<button
			class="subscription-confirm-button-back"
			type="button"
			onclick="location.href = '{{ route('profile') }}'"
		>
			プロフィールへ戻る
		</button>
	</div>
</div>
@endsection
