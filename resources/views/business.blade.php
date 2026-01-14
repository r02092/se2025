@extends('layouts.app')

@section('title', 'SceneTrip - 事業者機能')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/home.css') }}" />
<link href="https://unpkg.com/maplibre-gl@latest/dist/maplibre-gl.css" rel="stylesheet" />
@endpush

@section('content')
<h1>事業者機能</h1>

<div class="general-box" style="margin: 0 5% 0">
    <ul>
        <li><a href="{{ route('spot.edit') }}">スポット情報編集</a></li>
        <li><a href="{{ route('data') }}">観光データ確認</a></li>
        <li><a href="#">APIキー管理</a></li>
        <li><a href="#">請求書[PDF]</a></li>
    </ul>
</div>
@endsection
