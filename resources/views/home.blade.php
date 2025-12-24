@extends('layouts.app')

@section('title', 'SceneTrip - ホーム')

@push('styles')
<link href="https://unpkg.com/maplibre-gl@latest/dist/maplibre-gl.css" rel="stylesheet" />
@endpush

@section('content')
<!-- グラデーション トップ -->
<div class="gradient-top"></div>

<div class="map-area">
    <div id="map"></div>
</div>

<div class="general-box form-container" style="padding-top: 15px; padding-bottom: 20px; margin: 16px 5% 16px">
    <form action="{{ route('search') }}" method="GET">
        <label for="departure">出発地</label>
        <input type="text" id="departure" name="departure" />

        <label for="destination">到着地</label>
        <input type="text" id="destination" name="destination" />

        <button type="submit" style="margin: 10px 0 0">検索</button>
    </form>
</div>

<div class="general-box ai-suggest">
    <h2>人気のスポット</h2>
    <div class="spot-divider" aria-hidden="true"></div>

    <ul class="spot-list" aria-label="人気のスポット一覧">
        <li class="spot-item">
            <img class="spot-thumb" src="{{ asset('images/Harimaya_Bridge.jpg') }}" alt="はりまや橋" />
            <div class="spot-content">
                <h3 class="spot-title">はりまや橋</h3>
            </div>
        </li>
        <li class="spot-item">
            <img class="spot-thumb" src="{{ asset('images/post-station.jpg') }}" alt="土佐山田駅" />
            <div class="spot-content">
                <h3 class="spot-title">土佐山田駅</h3>
            </div>
        </li>
        <li class="spot-item">
            <img class="spot-thumb" src="{{ asset('images/ryugado.jpg') }}" alt="龍河洞" />
            <div class="spot-content">
                <h3 class="spot-title">龍河洞</h3>
            </div>
        </li>
    </ul>
</div>

<div class="suggest"></div>
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
