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

for (const i of document.querySelectorAll("[id^='tab-btn-']")) {
	i.addEventListener("click", e => {
		for (const i of document.querySelectorAll("[id^='tab-btn-']")) {
			const tab = (i as HTMLButtonElement).id.substring(8);
			const hide = e.currentTarget !== i;
			(
				document.getElementById("form-area-" + tab) as HTMLDivElement
			).style.display = hide ? "none" : "block";
			const iStyle = (i as HTMLButtonElement).style;
			iStyle.background = hide ? "" : "#fff";
			const color = hide ? "" : tab !== "ai" ? "#16a34a" : "#2563eb";
			iStyle.color = color;
			iStyle.borderBottomColor = color;
		}
	});
}
