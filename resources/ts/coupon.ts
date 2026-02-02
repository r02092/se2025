const viewSelect = document.getElementById(
	"view_select",
) as HTMLSelectElement | null;
const activeSection = document.getElementById(
	"active_coupons",
) as HTMLElement | null;
const availableSection = document.getElementById(
	"available_coupons",
) as HTMLElement | null;
const live = document.getElementById("view_live") as HTMLElement | null;
const sortSelect = document.getElementById("sort") as HTMLSelectElement | null;

// カテゴリフィルター用
const chips = document.querySelectorAll(".chip[data-cat]");
const couponCards = document.querySelectorAll(".coupon-card[data-category]");

// 並び替え関数
function applySorting(sortType: string): void {
	const availableSection = document.getElementById("available_coupons");
	if (!availableSection) return;

	const cards = Array.from(availableSection.querySelectorAll(".coupon-card"));

	cards.sort((a, b) => {
		if (sortType === "new") {
			// 新着順（作成日時の降順）
			const aCreated = parseInt(a.getAttribute("data-created") || "0");
			const bCreated = parseInt(b.getAttribute("data-created") || "0");
			return bCreated - aCreated;
		} else if (sortType === "exp") {
			// 期限が近い順（有効期限の昇順）
			const aExpires = parseInt(a.getAttribute("data-expires") || "9999999999");
			const bExpires = parseInt(b.getAttribute("data-expires") || "9999999999");
			return aExpires - bExpires;
		}
		return 0;
	});

	// 並び替えたカードを再配置
	cards.forEach(card => {
		availableSection.appendChild(card);
	});
}

// 並び替えセレクトのイベント
if (sortSelect) {
	sortSelect.addEventListener("change", () => {
		applySorting(sortSelect.value);
	});

	// 初期並び替えを適用
	applySorting(sortSelect.value);
}

// カテゴリフィルター関数
function applyCategoryFilter(category: string): void {
	couponCards.forEach(card => {
		const cardCategory = card.getAttribute("data-category");
		if (category === "all" || cardCategory === category) {
			(card as HTMLElement).style.display = "";
		} else {
			(card as HTMLElement).style.display = "none";
		}
	});
}

// チップボタンのクリックイベント
chips.forEach(chip => {
	chip.addEventListener("click", () => {
		const category = chip.getAttribute("data-cat");
		if (!category) return;

		// すべてのチップから active を削除
		chips.forEach(c => {
			c.classList.remove("active");
			c.setAttribute("aria-pressed", "false");
		});

		// クリックされたチップを active に
		chip.classList.add("active");
		chip.setAttribute("aria-pressed", "true");

		// フィルター適用
		applyCategoryFilter(category);
	});
});

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
