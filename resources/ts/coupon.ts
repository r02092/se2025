const viewSelect = document.getElementById(
	"view-select",
) as HTMLSelectElement | null;
const activeSection = document.getElementById(
	"active-coupons",
) as HTMLElement | null;
const availableSection = document.getElementById(
	"available-coupons",
) as HTMLElement | null;
const live = document.getElementById("view-live") as HTMLElement | null;

if (viewSelect && activeSection && availableSection) {
	function applyView(): void {
		if (!viewSelect || !activeSection || !availableSection) return;

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

	// --- ここから追加:  data-href を持つ coupon-card をクリック / キーボードで遷移 ---
	// クリックで遷移、Enter/Space でキーボード操作も可能にする（アクセシビリティ対応）
	const clickableCards = document.querySelectorAll(". coupon-card[data-href]");
	clickableCards.forEach((card: Element) => {
		const href = card.getAttribute("data-href");
		if (!href) return;

		// クリック
		card.addEventListener("click", (): void => {
			location.href = href;
		});

		// キーボード（Enter / Space）での有効化
		card.addEventListener("keydown", (e: Event): void => {
			const keyboardEvent = e as KeyboardEvent;
			if (
				keyboardEvent.key === "Enter" ||
				keyboardEvent.key === " " ||
				keyboardEvent.key === "Spacebar"
			) {
				keyboardEvent.preventDefault();
				location.href = href;
			}
		});
	});
}

export {};
