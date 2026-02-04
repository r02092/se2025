@extends('layouts.app')

@section('title', 'ユーザー一覧')

@section('content')
<h1>ユーザー一覧</h1>
<section class="coupon-list">
	<article class="general-box coupon-card" tabindex="0" role="article">
		<button class="users-btn" onclick="location.href = '{{ route('admin.users.create') }}'">
			ユーザーを追加
		</button>
	</article>
	@foreach ($users as $user)
	<article class="general-box coupon-card" tabindex="0" role="article" onclick="location.href='{{ route('admin.user.detail', ['id' => $user->id]) }}'" style="cursor: pointer;">
		<img class="coupon-thumb" src="{{ $user->icon_url }}">
		<div class="coupon-info">
			<h2 class="coupon-title">{{ $user->name }}</h2>
			<p class="coupon-desc">
				ID: {{ $user->id }}<br>
				ログイン名: {{ $user->login_name }}<br>
				種別:
				@if($user->permission === 0)
					管理者
				@elseif($user->permission === 1)
					利用者
				@elseif($user->permission === 2)
					承認済み事業者
				@else
					その他
				@endif
				<br>
			</p>
		</div>
	</article>
	@endforeach

</section>
@endsection
