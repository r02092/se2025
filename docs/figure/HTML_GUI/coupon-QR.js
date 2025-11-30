// coupon-QR: ページ読み込み時に main-area を暗くしてモーダル（QR）をポップアップ表示。
// 閉じるはボタン・背景クリック・Esc で行えるようにしています。

document.addEventListener("DOMContentLoaded", function () {
	const overlay = document.getElementById("qr-overlay");
	if (!overlay) return;

	const closeBtn = overlay.querySelector(".qr-modal-close");
	const closeLink = overlay.querySelector(".qr-modal-close-btn");

	function showOverlay() {
		overlay.classList.add("show");
		overlay.setAttribute("aria-hidden", "false");
		// フォーカスを閉じるボタンへ移す（アクセシビリティ）
		setTimeout(() => {
			const btn = overlay.querySelector(".qr-modal-close");
			if (btn) btn.focus();
		}, 50);
		// 背景スクロール抑止
		document.documentElement.style.overflow = "hidden";
		document.body.style.overflow = "hidden";
	}

	function hideOverlay() {
		overlay.classList.remove("show");
		overlay.setAttribute("aria-hidden", "true");
		document.documentElement.style.overflow = "";
		document.body.style.overflow = "";
		// フォーカスを戻す（戻るリンクがあれば）
		const backLink = document.querySelector(".qr-back");
		if (backLink) backLink.focus();
	}

	// 自動表示（ページロード時）
	showOverlay();

	// 閉じるボタン
	if (closeBtn) closeBtn.addEventListener("click", hideOverlay);

	// 右下の閉じるリンク（a要素）も閉じる挙動として扱う（リンク遷移はそのまま）
	if (closeLink) {
		closeLink.addEventListener("click", function (e) {
			// a タグはリンク遷移するため特段の処理は不要（もし遷移前に閉じたいなら preventDefault を使う）
		});
	}

	// 背景クリックで閉じる（モーダル本体クリックは無視）
	overlay.addEventListener("click", function (e) {
		if (e.target === overlay) {
			hideOverlay();
		}
	});

	// ESC で閉じる
	document.addEventListener("keydown", function (e) {
		if (e.key === "Escape" && overlay.classList.contains("show")) {
			hideOverlay();
		}
	});
});
