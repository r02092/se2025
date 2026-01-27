export default function (
	pcElem: HTMLInputElement,
	btnElem: HTMLButtonElement,
	prefElem: HTMLSelectElement,
	cityElem: HTMLSelectElement,
	addrElem: HTMLInputElement,
) {
	prefElem.addEventListener("change", e => {
		cityFilter(e.currentTarget as HTMLSelectElement);
	});
	btnElem.addEventListener("click", async () => {
		const addr = await (await fetch("/addr/" + pcElem.value)).json();
		[
			{elem: prefElem, value: Math.floor(addr.city / 1000)},
			{elem: cityElem, value: addr.city},
		].forEach(i => {
			for (const j of i.elem.options) {
				if (j.value === i.value.toString()) {
					j.selected = true;
				}
			}
		});
		addrElem.value = addr.addr;
		if (!addr.city) {
			alert("郵便番号から住所を推測できませんでした。");
		}
	});
	cityFilter(prefElem);
	function cityFilter(selectElem: HTMLSelectElement) {
		const pref = Number(selectElem.value);
		for (const i of cityElem.options) {
			i.hidden = pref !== Math.floor(Number(i.value) / 1000);
			if (i.hidden && i.selected) {
				i.selected = false;
			}
		}
	}
}
