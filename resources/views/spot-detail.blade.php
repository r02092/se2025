@extends('layouts.app')

@section('title', 'SceneTrip - スポット詳細')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/spot.css') }}" />
@endpush

@section('content')
<div class="general-box">
	<!-- 表示すべき項目 -->
	<!-- スポット名、カテゴリ、キーワード、説明、画像、場所（地図か住所）、投稿ユーザ名、評価、口コミ -->
</div>
@endsection
