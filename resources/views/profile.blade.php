@extends('layouts.app')

@section('title', 'SceneTrip - プロフィール')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/profile.css') }}" />
@endpush

@section('content')
<div class="general-box profile-container">
    <h2>プロフィール</h2>
    <div class="profile-avatar-group">
        <img src="{{ asset('images/' . (Auth::user()->profile_image ?? 'Profile_pic.JPG')) }}"
             alt="ユーザーのアバター画像"
             class="profile-avatar" />
        <span>現在のアバター画像</span>
    </div>
    <div class="profile-box">
        <dl class="profile">
            <dt>表示名</dt>
            <dd>{{ Auth::user()->name }}</dd>

            <dt>ログイン名</dt>
            <dd>{{ Auth::user()->username }}</dd>

            <dt>パスワード</dt>
            <dd>********</dd>

            <dt>二要素認証</dt>
            <dd>{{ Auth::user()->two_factor_enabled ? '設定済み' : '未設定' }}</dd>
        </dl>
    </div>
    <button class="profile-button-edit" onclick="location.href = '{{ route('profile.edit') }}'">
        プロフィールを編集
    </button>
</div>

<div class="general-box profile-subscription-container">
    <h3>事業者申込</h3>
    <button class="profile-button-subscription" onclick="location.href = '{{ route('subscription.form') }}'">
        登録
    </button>
</div>
@endsection
