@extends('layouts.app')

@section('title', 'クーポン作成・編集')

@push('scripts')
@vite(['resources/ts/coupon_edit.ts'])
@endpush

@section('content')
<h1>クーポン作成・編集</h1>
<div class="coupon-edit-header"><span>対象スポット</span>: <a href="{{ route('detail', ['id' => $spot->id]) }}">{{ $spot->name }}</a></div>
<section class="coupon-list edit">
	@for($i = -1; $i < count($coupons); $i++)
	@php
		if ($i + 1) {
			$coupon = $coupons[$i];
		} else {
			$coupon[0] = new \App\Models\Coupon();
			$coupon[1] = '';
		}
	@endphp
	<article
		class="general-box form-container"
		tabindex="0"
		role="article"
	>
		<form action="{{ route('business.coupon.upd') }}" method="POST">
			@csrf
			@if ($i < 0)
				<h2>新規クーポン</h2>
			@endif
			<input type="hidden" name="id" value="{{ $coupon[0]->id }}">
			<label for="name_{{ $coupon[0]->id }}">名前</label>
			<input type="text" id="name_{{ $coupon[0]->id }}" name="name" required value="{{ $coupon[0]->name }}">
			<label for="cond_{{ $coupon[0]->id }}">クーポン利用条件<span class="form-detail">（空欄で条件なし）</span></label>
			<div>
				<input type="text" id="cond_{{ $coupon[0]->id }}" name="cond" autocomplete="off" value="{{ $coupon[1] }}">
				<div id="cond_{{ $coupon[0]->id }}_suggest"></div>
			</div>
			にチェックインする
			<label for="expires_{{ $coupon[0]->id }}">有効期限</label>
			<input type="datetime-local" id="expires_{{ $coupon[0]->id }}" name="expires" min="{{ date('Y-m-d') }}T{{ date('H:i') }}" value="{{ $coupon[0]->expires_at }}">
			<button type="submit">{{ $i + 1 ? '更新' : '作成' }}</button>
		</form>
		@if ($i + 1)
		<form action="{{ route('business.coupon.del') }}" method="POST">
			@csrf
			<input type="hidden" name="id" value="{{ $coupon[0]->id }}">
			<button type="submit">削除</button>
		</form>
		@endif
	</article>
	@endfor
</section>
@endsection
