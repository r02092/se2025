document.getElementById("pref_select")?.addEventListener("change", e => {
	cityFilter(e.currentTarget as HTMLSelectElement);
});
document.getElementById("pc2addrbtn")?.addEventListener("click", async () => {
	const addr = await (
		await fetch(
			"/addr/" +
				(document.getElementById("post_code") as HTMLInputElement).value,
		)
	).json();
	[
		{id: "pref_select", value: Math.floor(addr.city / 1000)},
		{id: "city_select", value: addr.city},
	].forEach(i => {
		for (const j of (document.getElementById(i.id) as HTMLSelectElement)
			.options) {
			if (j.value === i.value.toString()) {
				j.selected = true;
			}
		}
	});
	(document.getElementById("address") as HTMLInputElement).value = addr.addr;
	if (!addr.city) {
		alert("郵便番号から住所を推測できませんでした。");
	}
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
