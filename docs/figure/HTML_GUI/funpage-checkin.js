// より堅牢な自動オーバーレイ表示スクリプト
// - DOM読み込み後に overlay を検索（複数の候補を試す）
// - 見つかれば自動で showOverlay() を呼ぶ（120ms 遅延）
// - 閉じるはボタン・背景クリック・Escで可能
// - デバッグ用の console.log 出力あり（動作確認後は消してOK）

document.addEventListener("DOMContentLoaded", function () {
	console.log("funpage-checkin-qr.js: loaded");

	// overlay を探す（id / class の候補を順に試す）
	const overlayCandidates = [
		"checkin-qr-overlay",
		"checkin-qr-overlay", // 同じが重複しても安全
		"qr-overlay",
		"checkin-overlay",
	];
	let overlay = null;
	for (const id of overlayCandidates) {
		const elById = document.getElementById(id);
		if (elById) {
			overlay = elById;
			break;
		}
	}
	// class 名でも探す
	if (!overlay) overlay = document.querySelector(".checkin-qr-overlay") || document.querySelector(".qr-overlay");

	if (!overlay) {
		console.error("funpage-checkin-qr.js: overlay element not found. Ensure the HTML contains an element with id/class like 'checkin-qr-overlay' or 'checkin-qr-overlay'.");
		return;
	}

	// 開閉ボタンやリンク
	const openTarget = document.getElementById("checkinBox") || document.querySelector(".checkin-box");
	const closeBtn = overlay.querySelector(".checkin-qr-close") || overlay.querySelector(".qr-modal-close") || overlay.querySelector(".qr-close");
	const actionCloseBtn = overlay.querySelector(".checkin-qr-close-btn") || overlay.querySelector(".qr-modal-close-btn") || null;

	// show / hide 実装
	function preventScroll() {
		document.documentElement.style.overflow = "hidden";
		document.body.style.overflow = "hidden";
	}
	function allowScroll() {
		document.documentElement.style.overflow = "";
		document.body.style.overflow = "";
	}

	function showOverlay() {
		if (!overlay) return;
		overlay.classList.add("show");
		overlay.setAttribute("aria-hidden", "false");
		preventScroll();
		// フォーカス移動（アクセシビリティ）
		setTimeout(() => {
			const btn = overlay.querySelector(".checkin-qr-close, .qr-modal-close, .qr-close");
			if (btn) btn.focus();
		}, 50);
		console.log("funpage-checkin-qr.js: overlay shown");
	}

	function hideOverlay() {
		if (!overlay) return;
		overlay.classList.remove("show");
		overlay.setAttribute("aria-hidden", "true");
		allowScroll();
		// フォーカス戻し
		if (openTarget) openTarget.focus();
		console.log("funpage-checkin-qr.js: overlay hidden");
	}

	// 自動表示（少し遅延）
	setTimeout(() => {
		try {
			showOverlay();
		} catch (err) {
			console.error("funpage-checkin-qr.js: showOverlay error", err);
		}
	}, 120);

	// 既存のトリガー（存在すれば）
	if (openTarget) {
		openTarget.addEventListener("click", function (e) {
			e.preventDefault();
			showOverlay();
		});
		openTarget.addEventListener("keydown", function (e) {
			if (e.key === "Enter" || e.key === " " || e.key === "Spacebar") {
				e.preventDefault();
				showOverlay();
			}
		});
	}

	// 閉じるボタン
	if (closeBtn) closeBtn.addEventListener("click", function (e) {
		e.preventDefault();
		hideOverlay();
	});
	if (actionCloseBtn) actionCloseBtn.addEventListener("click", function (e) {
		// actionCloseBtn は通常はリンクなので preventDefault をしないこともあるが、
		// ここでは閉じる挙動のみでよければ preventDefault を外しても良い
		e.preventDefault();
		hideOverlay();
	});

	// 背景クリックで閉じる（モーダル本体クリックは無視）
	overlay.addEventListener("click", function (e) {
		if (e.target === overlay) hideOverlay();
	});

	// ESC で閉じる
	document.addEventListener("keydown", function (e) {
		if (e.key === "Escape" && overlay.classList.contains("show")) {
			hideOverlay();
		}
	});

	// 小さなヘルプ: overlay が見つかって自動表示されない場合は
	// ブラウザの DevTools Console を開いて上の console.log / console.error を確認してください。
});