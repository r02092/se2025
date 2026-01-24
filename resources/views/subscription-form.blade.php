@extends('layouts.app')

@section('title', '事業者申込フォーム')

@push('scripts')
@vite(['resources/ts/subscription.ts'])
@endpush

@section('content')
<div class="main-area">
	<div class="general-box subscription-form-container">
		<h1 class="h2">事業者申込フォーム</h1>
		<form method="POST">
			@csrf
			<label for="post_code">郵便番号<span class="form-detail">（ハイフンなし）</span></label>
			<input type="text" id="post_code" name="post_code" required value="{{ old('post_code') }}" />
			<button type="button" id="pc2addrbtn" class="btn btn-secondary">
				郵便番号から住所を自動入力
			</button>

			<label for="pref_select">都道府県</label>
			<select name="pref" id="pref_select" required>
				<option value="">--1つ選択してください--</option>
				@foreach ($prefs as $prefId => $prefName)
					<option value="{{ $prefId }}" {{ old('pref') === $prefId ? 'selected' : '' }}>
						{{ $prefName }}
					</option>
				@endforeach
			</select>

			<label for="city_select">市区町村</label>
			<select name="city" id="city_select" required>
				<option value="">--1つ選択してください--</option>
				@foreach ($cities as $cityId => $cityName)
					<option value="{{ $cityId }}" {{ old('city') === $cityId ? 'selected' : '' }}>
						{{ $cityName }}
					</option>
				@endforeach
			</select>

			<label for="address">住所<span class="form-detail">（市区町村名より後のみ）</span></label>
			<input type="text" id="address" name="address" required value="{{ old('address') }}" />

			<label for="num_plan_std">スタンダードプランの契約数</label>
			<input type="number" id="num_plan_std" name="num_plan_std" min="0" max="4294967295" required value="{{ old('num_plan_std') }}" />

			<label for="num_plan_prm">プレミアムプランの契約数</label>
			<input type="number" id="num_plan_prm" name="num_plan_prm" min="0" max="4294967295" required value="{{ old('num_plan_prm') }}" />

			<button type="submit">
				申し込む
			</button>

			<button type="button" class="btn btn-secondary" onclick="location.href='{{ route('profile') }}'">
				キャンセル
			</button>
		</form>
	</div>
</div>
@endsection
