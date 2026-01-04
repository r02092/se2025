// より堅牢な自動オーバーレイ表示スクリプト
// - DOM読み込み後に overlay を検索（複数の候補を試す）
// - 見つかれば自動で showOverlay() を呼ぶ（120ms 遅延）
// - 閉じるはボタン・背景クリック・Escで可能
// - デバッグ用の console.log 出力あり（動作確認後は消してOK）

document.addEventListener("DOMContentLoaded", function (): void {
	console.log("funpage-checkin-qr.js: loaded");

	// overlay を探す（id / class の候補を順に試す）
	const overlayCandidates: string[] = [
		"checkin-qr-overlay",
		"checkin-qr-overlay", // 同じが重複しても安全
		"qr-overlay",
		"checkin-overlay",
	];
	let overlay: HTMLElement | null = null;
	for (const id of overlayCandidates) {
		const elById = document.getElementById(id);
		if (elById) {
			overlay = elById;
			break;
		}
	}
	// class 名でも探す
	if (!overlay)
		overlay =
			(document.querySelector(".checkin-qr-overlay") as HTMLElement) ||
			(document.querySelector(".qr-overlay") as HTMLElement);

	if (!overlay) {
		console.error(
			"funpage-checkin-qr.js: overlay element not found.  Ensure the HTML contains an element with id/class like 'checkin-qr-overlay' or 'checkin-qr-overlay'.",
		);
		return;
	}

	// 開閉ボタンやリンク
	const openTarget: HTMLElement | null =
		document.getElementById("checkinBox") ||
		(document.querySelector(".checkin-box") as HTMLElement);
	const closeBtn: HTMLElement | null =
		overlay.querySelector(".checkin-qr-close") ||
		overlay.querySelector(".qr-modal-close") ||
		overlay.querySelector(".qr-close");
	const actionCloseBtn: HTMLElement | null =
		overlay.querySelector(".checkin-qr-close-btn") ||
		overlay.querySelector(".qr-modal-close-btn") ||
		null;

	// show / hide 実装
	function preventScroll(): void {
		document.documentElement.style.overflow = "hidden";
		document.body.style.overflow = "hidden";
	}

	function allowScroll(): void {
		document.documentElement.style.overflow = "";
		document.body.style.overflow = "";
	}

	function showOverlay(): void {
		if (!overlay) return;
		overlay.classList.add("show");
		overlay.setAttribute("aria-hidden", "false");
		preventScroll();
		// フォーカス移動（アクセシビリティ）
		setTimeout((): void => {
			if (!overlay) return;
			const btn = overlay.querySelector(
				".checkin-qr-close, .qr-modal-close, .qr-close",
			) as HTMLElement | null;
			if (btn) btn.focus();
		}, 50);
		console.log("funpage-checkin-qr.js: overlay shown");
	}

	function hideOverlay(): void {
		if (!overlay) return;
		overlay.classList.remove("show");
		overlay.setAttribute("aria-hidden", "true");
		allowScroll();
		// フォーカス戻し
		if (openTarget) openTarget.focus();
		console.log("funpage-checkin-qr.js: overlay hidden");
	}

	// 自動表示（少し遅延）
	setTimeout((): void => {
		try {
			showOverlay();
		} catch (err) {
			console.error("funpage-checkin-qr.js: error showing overlay", err);
		}
	}, 120);

	// 閉じるボタン
	if (closeBtn) closeBtn.addEventListener("click", hideOverlay);
	if (actionCloseBtn) actionCloseBtn.addEventListener("click", hideOverlay);

	// 背景クリックで閉じる
	if (overlay) {
		overlay.addEventListener("click", function (e: MouseEvent): void {
			if (e.target === overlay) {
				hideOverlay();
			}
		});
	}

	// ESC で閉じる
	document.addEventListener("keydown", function (e: KeyboardEvent): void {
		if (e.key === "Escape" && overlay && overlay.classList.contains("show")) {
			hideOverlay();
		}
	});
});
