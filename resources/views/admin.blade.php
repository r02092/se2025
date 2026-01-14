@extends('layouts.app')

@section('title', 'SceneTrip - 管理者機能')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/home.css') }}" />
<link href="https://unpkg.com/maplibre-gl@latest/dist/maplibre-gl.css" rel="stylesheet" />
@endpush

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

@push('scripts')
<script src="https://unpkg.com/maplibre-gl@latest/dist/maplibre-gl.js"></script>
<script>
    const map = new maplibregl.Map({
        container: "map",
        style: "https://tile.openstreetmap.jp/styles/osm-bright-ja/style.json",
        center: [133.56, 33.3], // [経度, 緯度] 四国周辺
        zoom: 7.2,
    });
    map.addControl(new maplibregl.NavigationControl(), "top-right");
    new maplibregl.Marker().setLngLat([133.542639, 33.559944]).addTo(map);
    new maplibregl.Marker().setLngLat([133.685047, 33.607133]).addTo(map);
    new maplibregl.Marker().setLngLat([133.745187, 33.603579]).addTo(map);
</script>
@endpush
