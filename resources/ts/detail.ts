import maplibregl from "maplibre-gl";
import "maplibre-gl/dist/maplibre-gl.css";

const coord = document.getElementById("map")?.dataset as unknown as {
	lng: number;
	lat: number;
};

// マップの初期化
const map = new maplibregl.Map({
	container: "map",
	style: "https://tile.openstreetmap.jp/styles/osm-bright-ja/style.json",
	center: coord,
	zoom: 15,
});

map.addControl(new maplibregl.NavigationControl(), "top-right");
// ピン（マーカー）を立てる
new maplibregl.Marker().setLngLat(coord).addTo(map);

const dialog = document.getElementById("dialog") as HTMLDialogElement;

(document.getElementById("img") as HTMLImageElement).addEventListener(
	"click",
	() => dialog.showModal(),
);

document.body.addEventListener("click", e => {
	if ((e.target as HTMLImageElement).id !== "img") dialog.close();
});
