// coupon-selected のスライダー実装（クリックで Coupon-QR.html へ遷移するフォールバック追加）
// ハンドルは幅 80px を想定。ドラッグで動き、100% 到達で QR 表示（既存ロジック）。
// クリック（ドラッグ中でなければ）は Coupon-QR.html へ遷移します。

document.addEventListener("DOMContentLoaded", function () {
	const track = document.querySelector(".slider-track");
	const handle = document.querySelector(".slider-handle");
	const fill = document.querySelector(".slider-fill");
	// overlay は従来のモーダルを残していますが、クリックは別ページへ遷移します
	const overlay = document.getElementById("qr-overlay");
	const closeBtn = overlay?.querySelector(".qr-close");

	if (!track || !handle || !fill) return;

	let dragging = false;
	let trackRect = null;
	let handleWidth = 0;
	const PADDING = 6; // CSS と一致
	const THRESHOLD = 0.999; // 100% 判定（ほぼ 100%）

	// clientX 抽出（pointer/mouse/touch 対応）
	function getClientXFromEvent(e) {
		if (typeof e.clientX === "number") return e.clientX;
		if (e.touches && e.touches[0]) return e.touches[0].clientX;
		if (e.changedTouches && e.changedTouches[0]) return e.changedTouches[0].clientX;
		return null;
	}

	function resetHandle(animated = false) {
		const leftPx = PADDING;
		if (animated) {
			handle.style.transition = "left 240ms ease, transform 120ms ease";
			fill.style.transition = "width 240ms ease";
		} else {
			handle.style.transition = "";
			fill.style.transition = "";
		}
		handle.style.left = leftPx + "px";
		fill.style.width = "0%";
		if (animated) {
			setTimeout(() => {
				handle.style.transition = "";
				fill.style.transition = "";
			}, 300);
		}
	}

	function completeAndOpenQr() {
		const rect = track.getBoundingClientRect();
		const maxLeft = rect.width - handleWidth - PADDING;
		handle.style.transition = "left 120ms linear";
		fill.style.transition = "width 120ms linear";
		handle.style.left = maxLeft + "px";
		fill.style.width = "100%";
		// 完了時は既存のモーダルを使う（もし残っている場合）
		setTimeout(() => {
			if (overlay) {
				overlay.classList.add("show");
				overlay.setAttribute("aria-hidden", "false");
				document.body.style.overflow = "hidden";
				const firstFocusable = overlay.querySelector(".qr-close");
				firstFocusable?.focus();
			} else {
				// フォールバック: 別ページへ遷移
				location.href = "Coupon-QR.html";
			}
		}, 160);
	}

	function openQrPage() {
		// クリック時のフォールバック遷移
		location.href = "Coupon-QR.html";
	}

	function closeQr() {
		if (!overlay) return;
		overlay.classList.remove("show");
		overlay.setAttribute("aria-hidden", "true");
		document.body.style.overflow = "";
		resetHandle(true);
		handle.focus();
	}

	// start drag
	function startDrag(e) {
		e.preventDefault();
		dragging = true;
		trackRect = track.getBoundingClientRect();
		handleWidth = handle.offsetWidth || 80;
		try {
			if (e.pointerId && handle.setPointerCapture) handle.setPointerCapture(e.pointerId);
		} catch (err) {}
		handle.classList.add("dragging");
		handle.style.transition = "";
		fill.style.transition = "";
	}

	// move
	function doDrag(e) {
		if (!dragging) return;
		const clientX = getClientXFromEvent(e);
		if (clientX == null) return;
		let x = clientX - trackRect.left - handleWidth / 2;
		const min = PADDING;
		const max = trackRect.width - handleWidth - PADDING;
		if (x < min) x = min;
		if (x > max) x = max;
		const percent = (x - min) / (max - min);
		window.requestAnimationFrame(() => {
			handle.style.left = x + "px";
			fill.style.width = (percent * 100) + "%";
		});
	}

	// end
	function endDrag(e) {
		if (!dragging) return;
		dragging = false;
		handle.classList.remove("dragging");
		try {
			if (e.pointerId && handle.releasePointerCapture) handle.releasePointerCapture(e.pointerId);
		} catch (err) {}
		const computedLeft = parseFloat(getComputedStyle(handle).left || "0");
		const min = PADDING;
		const max = trackRect.width - handleWidth - PADDING;
		let percent = 0;
		if (max > min) percent = (computedLeft - min) / (max - min);
		if (percent >= THRESHOLD) {
			completeAndOpenQr();
		} else {
			resetHandle(true);
		}
	}

	// attach events (pointer/mouse/touch)
	handle.addEventListener("pointerdown", startDrag);
	window.addEventListener("pointermove", doDrag);
	window.addEventListener("pointerup", endDrag);
	window.addEventListener("pointercancel", endDrag);

	handle.addEventListener("mousedown", startDrag);
	window.addEventListener("mousemove", doDrag);
	window.addEventListener("mouseup", endDrag);

	handle.addEventListener("touchstart", startDrag, { passive: false });
	window.addEventListener("touchmove", doDrag, { passive: false });
	window.addEventListener("touchend", endDrag);

	// keyboard: Enter / Space -> complete
	handle.addEventListener("keydown", e => {
		if (e.key === "Enter" || e.key === " " || e.key === "Spacebar") {
			e.preventDefault();
			trackRect = track.getBoundingClientRect();
			handleWidth = handle.offsetWidth || 80;
			completeAndOpenQr();
		}
	});

	// クリックで即ページ遷移（ドラッグ中でなければ）
	handle.addEventListener("click", e => {
		e.stopPropagation();
		if (!handle.classList.contains("dragging")) {
			openQrPage();
		}
	});

	// overlay close handlers (もし overlay がある場合)
	if (closeBtn) closeBtn.addEventListener("click", closeQr);
	if (overlay) {
		overlay.addEventListener("click", e => {
			if (e.target === overlay) closeQr();
		});
	}
	document.addEventListener("keydown", e => {
		if (e.key === "Escape" && overlay && overlay.classList.contains("show")) closeQr();
	});

	// 初期化
	resetHandle(false);
	window.addEventListener("resize", () => resetHandle(false));
});