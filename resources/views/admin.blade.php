@extends('layouts.app')

@section('title', 'SceneTrip - 管理者機能')

@section('content')
<h1>管理者機能</h1>

<div class="general-box" style="margin: 0 5% 0">
    <ul>
        <li><a href="{{ route('user') }}">ユーザー一覧</a></li>
        <li><a href="#">UGC監視・管理</a></li>
        <li><a href="{{ route('spot.edit') }}">スポット情報編集</a></li>
        <li><a href="{{ route('data') }}">観光データ確認</a></li>
    </ul>
</div>
@endsection
