import loadImage from "blueimp-load-image";
import maplibregl from "maplibre-gl";
import "maplibre-gl/dist/maplibre-gl.css";

const map = new maplibregl.Map({
	container: "map",
	style: "https://tile.openstreetmap.jp/styles/osm-bright-ja/style.json",
	center: [133.719998, 33.620661],
	zoom: 9,
});
const marker = new maplibregl.Marker()
	.setLngLat([133.719998, 33.620661])
	.addTo(map);
map.addControl(new maplibregl.NavigationControl(), "top-right");
map.on("click", e => inputCoord(e.lngLat.toArray()));

const preview = document.getElementById("profile_preview") as HTMLImageElement;
document.getElementById("photo")?.addEventListener("change", e => {
	const file = (e.target as HTMLInputElement).files;
	if (file) {
		preview.setAttribute("src", URL.createObjectURL(file[0]));
		loadImage.parseMetaData(file[0], data => {
			const gpsInfo = data.exif?.get("GPSInfo") as unknown as
				| {
						get: (tagName: string) => number[];
				  }
				| undefined;
			if (gpsInfo) {
				inputCoord(
					["GPSLongitude", "GPSLatitude"].map(i =>
						gpsInfo
							.get(i)
							.map((e, j) => e / 60 ** j)
							.reduce((s, e) => s + e, 0),
					) as [number, number],
				);
			} else {
				document.getElementById("ud");
			}
		});
		preview.style.display = "block";
	} else {
		preview.style.display = "none";
	}
});
document.getElementById("location_btn")?.addEventListener("click", () => {
	navigator.geolocation.getCurrentPosition(c => {
		inputCoord([c.coords.longitude, c.coords.latitude]);
	});
});

function inputCoord(lngLat: [number, number]) {
	marker.setLngLat(lngLat);
	(document.getElementsByName("coord")[0] as HTMLInputElement).value =
		JSON.stringify(lngLat);
}
