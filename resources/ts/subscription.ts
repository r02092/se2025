document.getElementById("pref_select")?.addEventListener("change", e => {
	cityFilter(e.currentTarget as HTMLSelectElement);
});
cityFilter(document.getElementById("pref_select") as HTMLSelectElement);
function cityFilter(selectElem: HTMLSelectElement) {
	const pref = Number(selectElem.value);
	for (const i of (document.getElementById("city_select") as HTMLSelectElement)
		.options) {
		i.hidden = pref !== Math.floor(Number(i.value) / 1000);
		if (i.hidden && i.selected) {
			i.selected = false;
		}
	}
}
export {};
