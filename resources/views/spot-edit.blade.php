@extends('layouts.app')

@section('title', 'スポット作成・編集')

@push('scripts')
@vite(['resources/ts/spot_edit.ts'])
@endpush

@section('content')
<h1>スポット作成・編集</h1>
<section class="coupon-list spot-edit">
	@for($i = -1; $i < count($spots); $i++)
	@php
		if ($i + 1) {
			$spot = $spots[$i];
		} else {
			$spot = new \App\Models\Spot();
			$spot->lng = 133.719998;
			$spot->lat = 33.620661;
		}
	@endphp
	@if ($i + 1 || Auth::user()->permission === 0 || Auth::user()->num_plan_std + Auth::user()->num_plan_prm > count(Auth::user()->spots))
	<article
		class="general-box form-container"
		tabindex="0"
		role="article"
	>
		<form action="{{ route('business.spots.upd') }}" method="POST" enctype="multipart/form-data">
			@csrf
			@if ($i < 0)
				<h2>新規スポット</h2>
			@endif
			<input type="hidden" name="id" value="{{ $spot->id }}">
			<label for="name_{{ $spot->id }}">名前</label>
			<input type="text" id="name_{{ $spot->id }}" name="name" required value="{{ $spot->name }}">
			<label for="type_{{ $spot->id }}">種別</label>
			<div class="sort-select">
				<select id="type_{{ $spot->id }}" name="type">
					@foreach($types as $value => $type)
						<option value="{{ $value }}"{{ $spot->type === $value ? ' selected' : '' }}>{{ $type }}</option>
					@endforeach
				</select>
			</div>
			<label for="img_{{ $spot->id }}">画像</label>
			<img
				src="{{ isset($spot->img_ext) ? asset('storage/spots/' . $spot->id . '.' . $spot->img_ext) : asset('images/no-image.png') }}"
				id="img_preview_{{ $spot->id }}"
			>
			<input type="file" id="img_{{ $spot->id }}" name="img" class="photo-file" accept="image/*">
			<label for="description_{{ $spot->id }}">説明</label>
			<textarea  id="description_{{ $spot->id }}" name="description" rows="4" class="spot-detail-form-input">{{ $spot->description }}</textarea>
			<label for="pc_{{ $spot->id }}">郵便番号<span class="form-detail">（ハイフンなし）</span></label>
			<input type="text" id="pc_{{ $spot->id }}" name="pc" required value="{{ $spot->postal_code }}">
			<button type="button" id="pc2addrbtn_{{ $spot->id }}" class="btn btn-secondary">
				郵便番号から住所を自動入力
			</button>
			<label for="pref_select_{{ $spot->id }}">都道府県</label>
			<select name="pref" id="pref_select_{{ $spot->id }}" required>
				<option value="">--1つ選択してください--</option>
				@foreach ($prefs as $prefId => $prefName)
					<option value="{{ $prefId }}" {{ intdiv($spot->addr_city, 1000) === $prefId ? 'selected' : '' }}>
						{{ $prefName }}
					</option>
				@endforeach
			</select>
			<label for="city_select_{{ $spot->id }}">市区町村</label>
			<select name="city" id="city_select_{{ $spot->id }}" required>
				<option value="">--1つ選択してください--</option>
				@foreach ($cities as $cityId => $cityName)
					<option value="{{ $cityId }}" {{ $spot->addr_city === $cityId ? 'selected' : '' }}>
						{{ $cityName }}
					</option>
				@endforeach
			</select>
			<label for="address_{{ $spot->id }}">住所<span class="form-detail">（市区町村名より後のみ）</span></label>
			<input type="text" id="address_{{ $spot->id }}" name="address" required value="{{ $spot->addr_detail }}">
			<label for="map_{{ $spot->id }}">場所</label>
			<input type="hidden" id="coord_{{ $spot->id }}" name="coord" value="[{{ $spot->lng }},{{ $spot->lat }}]">
			<div id="map_{{ $spot->id }}" data-lng="{{ $spot->lng }}" data-lat="{{ $spot->lat }}"></div>
			<label for="plan_{{ $spot->id }}">プラン<span class="form-detail-strong">（変更する場合はスポット自体を削除する必要があります）</span></label>
			<select id="plan_{{ $spot->id }}" name="plan"{{ $i + 1 ? ' disabled' : ''}}>
				@foreach(['スタンダード', 'プレミアム'] as $value => $plan)
					<option value="{{ $value }}"{{ ($spot->plan === $value || ($value === 1 && $enablePlans === [false, true]) ? ' selected' : '') . ($i + 1 || $enablePlans[$value] ? '' : ' disabled') }}>{{ $plan }}プラン</option>
				@endforeach
			</select>
			<label>投稿者</label>
			@php
				$user = $spot->user ?? Auth::user()
			@endphp
			<a href="{{ route('user.detail', $user->id) }}">{{ $user->name }}</a>
			<button type="submit">{{ $i + 1 ? '更新' : '作成' }}</button>
		</form>
		@if ($i + 1)
		<form action="{{ route('business.spots.del') }}" method="POST">
			@csrf
			<input type="hidden" name="id" value="{{ $spot->id }}">
			<button type="submit">削除</button>
		</form>
		@endif
	</article>
	@endif
	@endfor
</section>
@endsection
