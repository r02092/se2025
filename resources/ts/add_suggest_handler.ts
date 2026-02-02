export default function (inputElem: HTMLInputElement) {
	inputElem.addEventListener("input", async e => {
		const input = e.currentTarget as HTMLInputElement;
		if (input.dataset.suggest !== undefined) return;
		input.dataset.suggest = "";
		const suggestElem = document.getElementById(
			input.id + "_suggest",
		) as HTMLDivElement;
		suggestElem.innerHTML = "";
		const res = await (await fetch("/filtering?keyword=" + input.value)).json();
		input.className = "input-spot-suggested";
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
				spotElem.addEventListener("mousedown", e => {
					e.preventDefault();
				});
				spotElem.addEventListener("click", e => {
					input.value = spotNameElem.textContent || "";
					suggestElem.innerHTML = "";
					input.className = "";
				});
				suggestElem.appendChild(spotElem);
			},
		);
		delete input.dataset.suggest;
	});
	inputElem.addEventListener("focusout", async e => {
		const input = e.target as HTMLInputElement;
		await new Promise(r => setTimeout(r, 99));
		(
			document.getElementById(input.id + "_suggest") as HTMLDivElement
		).innerHTML = "";
		input.className = "";
	});
}
