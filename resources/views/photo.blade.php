@extends('layouts.app')

@section('title', 'SceneTrip - フォト')

@section('content')
<!-- グラデーション トップ -->
<div class="gradient-top"></div>

<div class="map-area" style="padding: 0; height: 100vh">
	<div id="map" style="height: calc(100% - 97px)"></div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/maplibre-gl@latest/dist/maplibre-gl.js"></script>
<script>
	const map = new maplibregl.Map({
		container: "map",
		style: "https://tile.openstreetmap.jp/styles/osm-bright-ja/style.json",
		center: [133.685047, 33.607133], // [経度, 緯度] 四国周辺
		zoom: 13,
	});
	map.addControl(new maplibregl.NavigationControl(), "top-right");
	new maplibregl.Popup()
		.setLngLat([133.685047, 33.607133])
		.setHTML(
			`
				<header class="post-head">
					<img class="post-avatar" src="{{ asset('images/Profile_4.jpg') }}" />
					<div class="post-meta">
						<div class="post-author">ツル☆ハシ</div>
						<div class="post-time">18秒前</div>
					</div>
				</header>
				<img class="post-image" src="{{ asset('images/post-station.jpg') }}" />
				<div class="post-body">ついに香美市に到着！あのゲームにも出てきた場所、土佐山田駅だ！</div>
			`,
		)
		.addTo(map);
</script>
@endpush
