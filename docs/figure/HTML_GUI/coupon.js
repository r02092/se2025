document.addEventListener("DOMContentLoaded", function () {
	const viewSelect = document.getElementById("view-select");
	const activeSection = document.getElementById("active-coupons");
	const availableSection = document.getElementById("available-coupons");
	const live = document.getElementById("view-live");

	if (!viewSelect || !activeSection || !availableSection) return;

	function applyView() {
		const v = viewSelect.value;
		if (v === "all") {
			activeSection.classList.remove("hidden");
			availableSection.classList.remove("hidden");
		} else if (v === "active") {
			activeSection.classList.remove("hidden");
			availableSection.classList.add("hidden");
		} else if (v === "available") {
			activeSection.classList.add("hidden");
			availableSection.classList.remove("hidden");
		}
		if (live) {
			live.textContent =
				viewSelect.options[viewSelect.selectedIndex].text + " を表示しています";
		}
	}

	viewSelect.addEventListener("change", applyView);

	// 初期表示を適用
	applyView();
});
