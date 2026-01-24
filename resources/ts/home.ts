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
		const btnKeyword = document.getElementById(
			"tab-btn-keyword",
		) as HTMLButtonElement;
		const btnAi = document.getElementById("tab-btn-ai") as HTMLButtonElement;
		const areaKeyword = document.getElementById(
			"form-area-keyword",
		) as HTMLDivElement;
		const areaAi = document.getElementById("form-area-ai") as HTMLDivElement;

		if ((e.currentTarget as HTMLButtonElement).id === "tab-btn-keyword") {
			areaKeyword.style.display = "block";
			areaAi.style.display = "none";

			btnKeyword.style.background = "#fff";
			btnKeyword.style.color = "#16a34a";
			btnKeyword.style.borderBottomColor = "#16a34a";

			btnAi.style.background = "#f3f4f6";
			btnAi.style.color = "#6b7280";
			btnAi.style.borderBottomColor = "transparent";
		} else {
			areaKeyword.style.display = "none";
			areaAi.style.display = "block";

			btnAi.style.background = "#fff";
			btnAi.style.color = "#2563eb";
			btnAi.style.borderBottomColor = "#2563eb";

			btnKeyword.style.background = "#f3f4f6";
			btnKeyword.style.color = "#6b7280";
			btnKeyword.style.borderBottomColor = "transparent";
		}
	});
}
