@extends('layouts.app')

@section('title', '事業者機能')

@section('content')
<div class="main-area">
    <h1>事業者機能</h1>

    <div class="general-box" style="margin: 0 5% 0">
        <ul>
            <li><a href="#">スポット情報編集</a></li>
            <li><a href="#">観光データ確認</a></li>
            <li><a href="#">APIキー管理</a></li>
            <li><a href="#">請求書[PDF]</a></li>
        </ul>
    </div>
</div>
@endsection

@section('scripts')
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
@endsection
