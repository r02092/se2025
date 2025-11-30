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

	// --- ここから追加: data-href を持つ coupon-card をクリック / キーボードで遷移 ---
	// クリックで遷移、Enter/Space でキーボード操作も可能にする（アクセシビリティ対応）
	const clickableCards = document.querySelectorAll(".coupon-card[data-href]");
	clickableCards.forEach(card => {
		const href = card.getAttribute("data-href");
		if (!href) return;

		// クリック
		card.addEventListener("click", () => {
			location.href = href;
		});

		// キーボード（Enter / Space）での有効化
		card.addEventListener("keydown", e => {
			if (e.key === "Enter" || e.key === " " || e.key === "Spacebar") {
				e.preventDefault();
				location.href = href;
			}
		});
	});
});