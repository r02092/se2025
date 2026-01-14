import maplibregl from "maplibre-gl";
import "maplibre-gl/dist/maplibre-gl.css";
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
