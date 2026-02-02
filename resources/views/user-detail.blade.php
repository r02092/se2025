@extends('layouts.app')

@section('title', 'ユーザー詳細')

@push('scripts')
@vite(['resources/ts/user-detail.ts'])
@endpush

@section('content')
<div class="post-titlebar">
	<h1>ユーザー詳細</h1>
	<div class="post-titlebar-update">
		<button type="button" onclick="location.href = '{{ route('admin.user.detail', $user->id) }}'">
			内容更新
		</button>
	</div>
</div>

<div class="general-box profile-edit-container" style="margin-bottom: 100px;">
	<h2 class="h2">ユーザ情報編集</h1>

	{{-- エラー表示用（デバッグに役立ちます） --}}
	@if ($errors->any())
		<div class="error-msg">
			<ul>
				@foreach ($errors->all() as $error)
					<li>{{ $error }}</li>
				@endforeach
			</ul>
		</div>
	@endif

	{{-- 修正：画像送信のために enctype="multipart/form-data" を追加 --}}
	<form method="POST" action="{{ route('admin.user.update') }}" id="user_detail" enctype="multipart/form-data" data-perm="{{ $user->permission }}">
		@csrf

		<div class="profile-edit-avatar-group">
			<img
				id="profile_preview"
				src="{{ $user->icon_url }}"
				alt="ユーザーの現在のアバター画像"
				id="profile_preview"
				class="profile-avatar"
			>
			{{-- 修正：nameを 'icon' に変更（コントローラーのuploadIconと合わせる） --}}
			<input
				type="file"
				accept="image/png, image/jpeg"
				id="avatar_img"
				name="icon"
			>
			<label for="avatar_img">画像をアップロード</label>
		</div>

		<label for="username">表示名</label>
		{{-- 重要：nameを 'name' に変更 --}}
		<input
			type="text"
			id="username"
			name="name"
			required
			value="{{ old('name', $user->name) }}"
		>

		{{-- ログインID編集 --}}
		<label for="login_name">ログインID</label>
		<input
			type="text"
			id="login_name"
			name="login_name"
			required
			value="{{ old('login_name', $user->login_name) }}"
		>

		{{-- 種別編集 --}}
		<label for="pet-select">種別</label>
		<select name="permission" id="pet-select">
			<option value="{{ \App\Models\User::PERMISSION_ADMIN }}"
				@if($user->permission == \App\Models\User::PERMISSION_ADMIN)
					selected
				@endif
			>管理者</option>
			<option value="{{ \App\Models\User::PERMISSION_USER }}"
				@if($user->permission == \App\Models\User::PERMISSION_USER)
					selected
				@endif
			>利用者</option>
			<option value="{{ \App\Models\User::PERMISSION_BUSINESS }}"
				@if($user->permission == \App\Models\User::PERMISSION_BUSINESS)
					selected
				@endif
			>承認済み事業者</option>
		</select>

		<label>プラン契約数</label>
		<div style="padding-left: 3rem;">
			@if($user->permission == App\Models\User::PERMISSION_BUSINESS)
				{{-- スタンダードプランの件約数 --}}
				<label for="num_plan_std">スタンダードプランの契約数</label>
				<input type="number" id="num_plan_std" name="num_plan_std" min="0" max="4294967295" required value="{{ old('num_plan_std', $user->num_plan_std) }}">

				{{-- プレミアムプランの件約数 --}}
				<label for="num_plan_prm">プレミアムプランの契約数</label>
				<input type="number" id="num_plan_prm" name="num_plan_prm" min="0" max="4294967295" required value="{{ old('num_plan_prm', $user->num_plan_prm) }}">
			@else
				事業者ではないため未設定
				{{-- サーバー側で検証が通るようにダミーの値を埋め込んでおく --}}
				<input type="hidden" name="num_plan_std" value="0">
				<input type="hidden" name="num_plan_prm" value="0">
			@endif
		</div>

		<label>住所情報</label>
		<div style="padding-left: 3rem;">
			@if($user->permission == App\Models\User::PERMISSION_BUSINESS)
				{{-- 郵便番号 --}}
				<label for="postal_code">郵便番号<span class="form-detail">（ハイフンなし）</span></label>
				<input type="text" id="post_code" name="postal_code" required value="{{ old('postal_code', $user->postal_code) }}">
				<button type="button" id="pc2addrbtn" class="btn btn-secondary" style="width: 30%; font-size: 0.8rem">
					郵便番号から住所を自動入力
				</button>

				{{-- 都道府県 --}}
				<label for="pref_select">都道府県</label>
				<select name="pref" id="pref_select" required>\
					@foreach ($prefs as $prefId => $prefName)
						<option value="{{ $prefId }}" {{ old('pref', intdiv($user->addr_city, 1000)) == $prefId ? 'selected' : '' }}>
							{{ $prefName }}
						</option>
					@endforeach
				</select>

				{{-- 市町村 --}}
				<label for="city_select">市区町村</label>
				<select name="city" id="city_select" required>\
					@foreach ($cities as $cityId => $cityName)
						<option value="{{ $cityId }}" {{ $user->addr_city == $cityId ? 'selected' : '' }}>
							{{ $cityName }}
						</option>
					@endforeach
				</select>

				{{-- 住所 --}}
				<label for="address">住所<span class="form-detail">（市区町村名より後のみ）</span></label>
				<input type="text" id="address" name="address" value="{{ old('address', $user->addr_detail) }}">
			@else
				事業者ではないため未設定
				{{-- サーバー側で検証が通るようにダミーの値を埋め込んでおく --}}
				<input type="hidden" name="postal_code" value="0000000">
				<input type="hidden" name="city" value="1100">
				<input type="hidden" name="address" value="----">
			@endif
		</div>

		<label>二要素認証</label>
		<div>{{ $user->totp_secret ? '設定済み' : '未設定' }}</div>

		<label>作成日時</label>
		<div>{{ $user->created_at }}</div>

		<label>最終更新日時</label>
		<div>{{ $user->updated_at }}</div>

		{{-- ユーザーID --}}
		<input type="hidden" name="id" value="{{ $user->id }}">

		{{-- 確認付きボタン --}}
		<button type="submit" style="margin: 5% 0% 5% 0%;">
			登録情報変更
		</button onclick="return confirm('このユーザーの登録情報を変更しますか？')">
	</form>
</div>

<div class="general-box profile-subscription-container">
	<h3> </h3>
	<form action="{{ route('admin.user.delete') }}" method="POST">
		@csrf
		<input type="hidden" name="id" value="{{ $user->id }}">
		<button type="submit" class="profile-button-subscription" style="background: red;" onclick="return confirm('この投稿を削除しますか?')">
			ユーザーを削除
		</button>
	</form>
</div>
@endsection
