@extends('layouts.app')

@section('title', '投稿')

@push('scripts')
@vite(['resources/ts/photo.ts'])
@endpush

@section('content')
<!-- グラデーション トップ -->
<div class="gradient-top"></div>

<div id="map" style="padding: 0; height: calc(100vh - (var(--header-height) + var(--bottom-bar-height)));"></div>
<a href="{{ route('post.form') }}" class="photo-btn"></a>
@endsection
