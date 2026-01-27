import addImageHandler from "./profile_icon_handler";
import addAddrHandler from "./addr";
import maplibregl from "maplibre-gl";
import "maplibre-gl/dist/maplibre-gl.css";

for (const i of document.querySelectorAll("[id^='map_']")) {
	const coord = (i as HTMLDivElement).dataset as unknown as {
		lng: number;
		lat: number;
	};
	const map = new maplibregl.Map({
		container: i.id,
		style: "https://tile.openstreetmap.jp/styles/osm-bright-ja/style.json",
		center: coord,
		zoom: 16,
	});
	map.addControl(new maplibregl.NavigationControl(), "top-right");
	const marker = new maplibregl.Marker().setLngLat(coord).addTo(map);
	const idStr = i.id.substring(4);
	map.on("click", e => {
		const lngLat = e.lngLat.toArray();
		marker.setLngLat(lngLat);
		(document.getElementById("coord_" + idStr) as HTMLInputElement).value =
			JSON.stringify(lngLat);
	});
	const img = document.getElementById("img_" + idStr) as HTMLInputElement;
	const preview = document.getElementById(
		"img_preview_" + idStr,
	) as HTMLImageElement;
	addImageHandler(img, preview);
	img.addEventListener("change", () => {
		const file = img.files;
		if (file) {
			preview.setAttribute("src", URL.createObjectURL(file[0]));
		}
	});
	addAddrHandler(
		document.getElementById("pc_" + idStr) as HTMLInputElement,
		document.getElementById("pc2addrbtn_" + idStr) as HTMLButtonElement,
		document.getElementById("pref_select_" + idStr) as HTMLSelectElement,
		document.getElementById("city_select_" + idStr) as HTMLSelectElement,
		document.getElementById("address_" + idStr) as HTMLInputElement,
	);
}
