@extends('layouts.app')

@section('title', 'SceneTrip - 管理者機能')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/home.css') }}" />
@endpush

@section('content')
<h1>管理者機能</h1>

<div class="general-box ai-suggest">
    <h2>管理メニュー</h2>
    <div class="spot-divider" aria-hidden="true"></div>

    <ul class="spot-list" aria-label="管理機能一覧">
        <li class="spot-item">
            <a href="{{ route('user') }}" style="display: flex; align-items: center; text-decoration: none; color: inherit;">
                <div class="spot-content">
                    <h3 class="spot-title">ユーザー一覧</h3>
                    <p class="spot-desc">登録ユーザーの管理・確認</p>
                </div>
            </a>
        </li>
        <li class="spot-item">
            <a href="#" style="display: flex; align-items: center; text-decoration: none; color: inherit;">
                <div class="spot-content">
                    <h3 class="spot-title">UGC監視・管理</h3>
                    <p class="spot-desc">投稿コンテンツの監視と管理</p>
                </div>
            </a>
        </li>
        <li class="spot-item">
            <a href="{{ route('spot.edit') }}" style="display: flex; align-items: center; text-decoration: none; color: inherit;">
                <div class="spot-content">
                    <h3 class="spot-title">スポット情報編集</h3>
                    <p class="spot-desc">観光スポットの情報編集</p>
                </div>
            </a>
        </li>
        <li class="spot-item">
            <a href="{{ route('data') }}" style="display: flex; align-items: center; text-decoration: none; color: inherit;">
                <div class="spot-content">
                    <h3 class="spot-title">観光データ確認</h3>
                    <p class="spot-desc">統計データと分析結果の確認</p>
                </div>
            </a>
        </li>
    </ul>
</div>
@endsection
