// coupon-QR:  ページ読み込み時に main-area を暗くしてモーダル（QR）をポップアップ表示。
// 閉じるはボタン・背景クリック・Esc で行えるようにしています。

const overlay = document.getElementById("qr_overlay") as HTMLElement | null;

if (overlay) {
	const closeBtn = overlay.querySelector(
		".qr-modal-close",
	) as HTMLElement | null;
	const closeLink = overlay.querySelector(
		".qr-modal-close-btn",
	) as HTMLAnchorElement | null;

	function showOverlay(): void {
		if (!overlay) return;

		overlay.classList.add("show");
		overlay.setAttribute("aria-hidden", "false");
		// フォーカスを閉じるボタンへ移す（アクセシビリティ）
		setTimeout((): void => {
			const btn = overlay.querySelector(
				".qr-modal-close",
			) as HTMLElement | null;
			if (btn) btn.focus();
		}, 50);
		// 背景スクロール抑止
		document.documentElement.style.overflow = "hidden";
		document.body.style.overflow = "hidden";
	}

	function hideOverlay(): void {
		if (!overlay) return;

		overlay.classList.remove("show");
		overlay.setAttribute("aria-hidden", "true");
		document.documentElement.style.overflow = "";
		document.body.style.overflow = "";
		// フォーカスを戻す（戻るリンクがあれば）
		const backLink = document.querySelector(".qr-back") as HTMLElement | null;
		if (backLink) backLink.focus();
	}

	// 自動表示（ページロード時）
	showOverlay();

	// 閉じるボタン
	if (closeBtn) closeBtn.addEventListener("click", hideOverlay);

	// 右下の閉じるリンク（a要素）も閉じる挙動として扱う（リンク遷移はそのまま）
	if (closeLink) {
		closeLink.addEventListener("click", function (e: MouseEvent): void {
			// a タグはリンク遷移するため特段の処理は不要（もし遷移前に閉じたいなら preventDefault を使う）
		});
	}

	// 背景クリックで閉じる（モーダル本体クリックは無視）
	overlay.addEventListener("click", function (e: MouseEvent): void {
		if (e.target === overlay) {
			hideOverlay();
		}
	});

	// ESC で閉じる
	document.addEventListener("keydown", function (e: KeyboardEvent): void {
		if (e.key === "Escape" && overlay.classList.contains("show")) {
			hideOverlay();
		}
	});
}
