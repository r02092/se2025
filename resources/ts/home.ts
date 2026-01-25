import maplibregl from "maplibre-gl";
import "maplibre-gl/dist/maplibre-gl.css";

const icon = new Image();
icon.src = "/images/spot_icon.svg";
const map = new maplibregl.Map({
	container: "map",
	style: "https://tile.openstreetmap.jp/styles/osm-bright-ja/style.json",
	center: [133.56, 33.3], // [経度, 緯度] 四国周辺
	zoom: 7.2,
});
await new Promise(resolve => {
	icon.onload = resolve;
});
map.addControl(new maplibregl.NavigationControl(), "top-right");
map.addImage("spot_icon", icon);
map.on("load", () => {
	map.addSource("spots", {
		type: "geojson",
		data: {
			type: "FeatureCollection",
			features: JSON.parse(
				document.getElementById("map")?.dataset.spots as string,
			).map((i: {name: string; lng: number; lat: number}) => ({
				type: "Feature",
				properties: i,
				geometry: {
					type: "Point",
					coordinates: [i.lng, i.lat],
				},
			})),
		},
	});
	map.addLayer({
		id: "spots_layer",
		type: "symbol",
		source: "spots",
		layout: {
			"icon-image": "spot_icon",
			"icon-size": 0.1,
		},
	});
	map.on("click", "spots_layer", e => {
		const feature = (e.features as maplibregl.MapGeoJSONFeature[])[0];
		const coords = (feature.geometry as GeoJSON.Point).coordinates;
		while (Math.abs(e.lngLat.lng - coords[0]) > 180) {
			coords[0] += e.lngLat.lng > coords[0] ? 360 : -360;
		}
		new maplibregl.Popup({
			offset: 10,
			closeButton: false,
		})
			.setLngLat(coords as [number, number])
			.setHTML(
				"<a href='detail?id=" +
					feature.properties.id +
					"'>" +
					feature.properties.name +
					"</a>",
			)
			.addTo(map);
	});
});

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

for (const i of document.querySelectorAll(
	"#form-area-ai > form > div > input",
)) {
	i.addEventListener("input", async e => {
		const input = e.currentTarget as HTMLInputElement;
		if (input.dataset.suggest !== undefined) return;
		input.dataset.suggest = "";
		const suggestElem = document.getElementById(
			input.id + "_suggest",
		) as HTMLDivElement;
		suggestElem.innerHTML = "";
		const res = await (await fetch("/filtering?keyword=" + input.value)).json();
		input.className = "home-input-suggested";
		res.forEach(
			(j: {
				id: number;
				name: string;
				description: string;
				img_ext: string | null;
				keywords: {
					id: number;
					spot_id: number;
					keyword: string;
					created_at: string | null;
					updated_at: string | null;
					deleted_at: string | null;
				}[];
			}) => {
				const spotElem = document.createElement("div");
				const spotNameElem = document.createElement("div");
				spotNameElem.innerHTML = j.name.replace(
					input.value,
					"<span>" + input.value + "</span>",
				);
				spotElem.appendChild(spotNameElem);
				const keywordsElem = document.createElement("div");
				j.keywords.forEach(k => {
					if (k.keyword.indexOf(input.value) >= 0) {
						const keywordElem = document.createElement("div");
						keywordElem.innerHTML = k.keyword.replace(
							input.value,
							"<span>" + input.value + "</span>",
						);
						keywordsElem.appendChild(keywordElem);
					}
				});
				spotElem.appendChild(keywordsElem);
				spotElem.addEventListener("click", e => {
					input.value = (
						(e.currentTarget as HTMLDivElement).firstChild as HTMLDivElement
					).textContent;
					suggestElem.innerHTML = "";
					input.className = "";
				});
				suggestElem.appendChild(spotElem);
			},
		);
		delete input.dataset.suggest;
	});
	i.addEventListener("focusout", async e => {
		const input = e.target as HTMLInputElement;
		await new Promise(r => setTimeout(r, 99));
		(
			document.getElementById(input.id + "_suggest") as HTMLDivElement
		).innerHTML = "";
		input.className = "";
	});
}
