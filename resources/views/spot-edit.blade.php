@extends('layouts.app')

@section('title', 'SceneTrip - スポット編集')

@section('content')
<h1>スポット編集</h1>
<section class="coupon-list">
    <article
        class="general-box form-container"
        tabindex="0"
        role="article"
        style="margin: 0; width: 90%"
    >
        <form>
            <label>名前</label>
            <input type="text" required value="はりまや橋" />
            <label>種別</label>
            <div class="sort-select">
                <select name="sort">
                    <option>観光</option>
                    <option>体験アクティビティ</option>
                </select>
            </div>
            <label>画像</label>
            <img
                src="{{ asset('images/Harimaya_Bridge.jpg') }}"
                style="border-radius: 8px; width: 100%"
            />
            <button class="btn-ghost" style="width: 100%">画像を変更</button>
            <label>場所</label>
            <div id="map" style="border-radius: 8px; height: 240px"></div>
            <label>投稿者</label>
            <a href="#">Share</a>
            <button type="submit" style="margin: 16px 0">更新</button>
            <button type="submit" style="background: #f22727">削除</button>
        </form>
    </article>
    <article
        class="general-box form-container"
        tabindex="0"
        role="article"
        style="margin: 0; width: 90%"
    >
        <form>
            <label>名前</label>
            <input type="text" required value="龍河洞" />
            <label>種別</label>
            <div class="sort-select">
                <select name="sort">
                    <option>体験アクティビティ</option>
                </select>
            </div>
            <label>画像</label>
            <img src="{{ asset('images/ryugado.jpg') }}" style="border-radius: 8px; width: 100%" />
        </form>
    </article>
</section>
@endsection

@push('scripts')
<script src="https://unpkg.com/maplibre-gl@latest/dist/maplibre-gl.js"></script>
<script>
    const map = new maplibregl.Map({
        container: "map",
        style: "https://tile.openstreetmap.jp/styles/osm-bright-ja/style.json",
        center: [133.542639, 33.559944],
        zoom: 16,
    });
    map.addControl(new maplibregl.NavigationControl(), "top-right");
    new maplibregl.Marker().setLngLat([133.542639, 33.559944]).addTo(map);
</script>
@endpush
