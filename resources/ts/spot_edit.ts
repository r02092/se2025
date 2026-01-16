import maplibregl from "maplibre-gl";
import "maplibre-gl/dist/maplibre-gl.css";
const map = new maplibregl.Map({
	container: "map",
	style: "https://tile.openstreetmap.jp/styles/osm-bright-ja/style.json",
	center: [133.542639, 33.559944],
	zoom: 16,
});
map.addControl(new maplibregl.NavigationControl(), "top-right");
new maplibregl.Marker().setLngLat([133.542639, 33.559944]).addTo(map);
