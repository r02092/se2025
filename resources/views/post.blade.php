@extends('layouts.app')

@section('title', 'SceneTrip - 投稿')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/post.css') }}" />
@endpush

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
            <img class="post-avatar" src="{{ asset('images/' . $post->user->avatar) }}" alt="avatar" />
            <div class="post-meta">
                <div class="post-author">{{ $post->user->name }}</div>
                <div class="post-time">{{ $post->created_at->diffForHumans() }}</div>
            </div>
        </header>
        @if($post->image)
        <img class="post-image" src="{{ asset('storage/' . $post->image) }}" alt="" />
        @endif
        <div style="color: #ddcc00; margin-top: 8px">{{ str_repeat('★', $post->rating) }}</div>
        <div class="post-body">{{ $post->content }}</div>
        <div style="color: #aaa; font-size: 0.6em">
            種別: {{ $post->type }}<br />
            ID: {{ $post->id }}<br />
            投稿者: <a href="{{ route('user.detail', $post->user_id) }}">{{ $post->user->username }}</a><br />
            閲覧数: {{ $post->views }}<br />
        </div>
        <form action="{{ route('post.delete', $post->id) }}" method="POST" style="display: inline;">
            @csrf
            @method('DELETE')
            <button type="submit" class="comment-send" style="background: #f22727; margin-top: 16px"
                    onclick="return confirm('この投稿を削除しますか?')">
                削除
            </button>
        </form>
    </article>
    @endforeach
</section>
@endsection

@push('scripts')
<script src="{{ asset('js/post.js') }}"></script>
@endpush
