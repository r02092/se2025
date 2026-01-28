@extends('layouts.app')

@section('title', 'UGC監視・管理')

@section('content')
<div class="post-titlebar">
	<h1>UGC監視・管理</h1>
	<div class="post-titlebar-update">
		<button type="button" onclick="location.href = '{{ route('post') }}'">
			内容更新
		</button>
	</div>
</div>

<!-- フィード（投稿カードをここに差し込む） -->
<section id="feed" class="feed">
	@foreach($posts ?? [] as $post)
	<article class="general-box post-card">
		<header class="post-head">
			<img class="post-avatar" src="{{ asset('images/' . $post['data']->user->id) }}" alt="avatar">
			<div class="post-meta">
				<div class="post-author">{{ $post['data']->user->name }}</div>
				<div class="post-time">{{ $post['data']->created_at->diffForHumans() }}</div>
			</div>
		</header>
		@if($post['data']->image)
		<img class="post-image" src="{{ asset('storage/' . $post['data']->image) }}" alt="">
		@endif
		<div style="color: #ddcc00; margin-top: 8px">{{ str_repeat('★', $post['data']->rate) }}</div>
		<div class="post-body">{{ $post['data']->comment }}</div>
		<div style="color: #aaa; font-size: 0.6em">
			種別: {{ $post['type'] !== 'photo' ? '口コミ' : '写真' }}<br>
			ID: {{ $post['data']->id }}<br>
			投稿者: <a href="{{ route('user.detail', $post['data']->user->id) }}">{{ $post['data']->user->name }}</a><br>
			@if($post['type'] !== 'photo')
			閲覧数: {{ $post['data']->views }}<br>
			@else
			経緯度: {{ $post['data']->lng }},{{ $post['data']->lat }}<br>
			@endif
			IPアドレス/ポート: {{ $post['data']->ip_addr }}:{{ $post['data']->port }}<br>
			User-Agent: {{ $post['data']->user_agent }}<br>
		</div>
		<form action="{{ route('admin.ugc.del') }}" method="POST" style="display: inline;">
			@csrf
			<input type="hidden" name="type" value="{{ $post['type'] }}">
			<input type="hidden" name="id" value="{{ $post['data']->id }}">
			<button type="submit" class="comment-send" onclick="return confirm('この投稿を削除しますか?')">
				削除
			</button>
		</form>
	</article>
	@endforeach
</section>
@endsection
