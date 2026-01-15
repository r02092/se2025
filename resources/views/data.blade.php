@extends('layouts.app')

@section('title', 'SceneTrip - 観光データ')

@section('content')
<h1>観光データ</h1>

<div class="general-box ai-suggest">
    <h2>人気のスポット</h2>
    <div class="spot-divider" aria-hidden="true"></div>

    <ul class="spot-list" aria-label="人気のスポット一覧">
        <li class="spot-item">
            <img
                class="spot-thumb"
                src="{{ asset('images/Harimaya_Bridge.jpg') }}"
                alt="はりまや橋"
            />
            <div class="spot-content">
                <h3 class="spot-title">はりまや橋</h3>
                <h3 class="spot-desc">過去1週間のスタンプ数: 2</h3>
            </div>
        </li>
        <li class="spot-item">
            <img class="spot-thumb" src="{{ asset('images/post-station.jpg') }}" alt="はりまや橋" />
            <div class="spot-content">
                <h3 class="spot-title">土佐山田駅</h3>
                <h3 class="spot-desc">過去1週間のスタンプ数: 1</h3>
            </div>
        </li>
        <li class="spot-item">
            <img class="spot-thumb" src="{{ asset('images/ryugado.jpg') }}" alt="はりまや橋" />
            <div class="spot-content">
                <h3 class="spot-title">龍河洞</h3>
                <h3 class="spot-desc">過去1週間のスタンプ数: 1</h3>
            </div>
        </li>
    </ul>
</div>
<div class="general-box ai-suggest" style="margin-top: 24px">
    <h2>人気の口コミが多い場所</h2>
    <div class="spot-divider" aria-hidden="true"></div>

    <ul class="spot-list" aria-label="人気のスポット一覧">
        <li class="spot-item">
            <img class="spot-thumb" src="{{ asset('images/post-cafe.jpg') }}" alt="はりまや橋" />
            <div class="spot-content">
                <h3 class="spot-title">高知工科大学 香美食堂</h3>
                <h3 class="spot-desc">口コミの閲覧合計数: 24</h3>
            </div>
        </li>
        <li class="spot-item">
            <img class="spot-thumb" src="{{ asset('images/ryugado.jpg') }}" alt="はりまや橋" />
            <div class="spot-content">
                <h3 class="spot-title">龍河洞</h3>
                <h3 class="spot-desc">口コミの閲覧合計数: 18</h3>
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
