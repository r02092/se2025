// coupon-selected のスライダー実装（クリックで Coupon-QR. html へ遷移するフォールバック追加）
// ハンドルは幅 80px を想定。ドラッグで動き、100% 到達で QR 表示（既存ロジック）。
// クリック（ドラッグ中でなければ）は Coupon-QR.html へ遷移します。

const track = document.querySelector(".slider-track") as HTMLElement | null;
const handle = document.querySelector(".slider-handle") as HTMLElement | null;
const fill = document.querySelector(".slider-fill") as HTMLElement | null;
// overlay は従来のモーダルを残していますが、クリックは別ページへ遷移します
const overlay = document.getElementById("qr-overlay") as HTMLElement | null;
const closeBtn = overlay?.querySelector(".qr-close") as HTMLElement | null;

if (track && handle && fill) {
	let dragging = false;
	let trackRect: DOMRect | null = null;
	let handleWidth = 0;
	const PADDING = 6; // CSS と一致
	const THRESHOLD = 0.999; // 100% 判定（ほぼ 100%）

	// clientX 抽出（pointer/mouse/touch 対応）
	function getClientXFromEvent(
		e: MouseEvent | TouchEvent | PointerEvent,
	): number | null {
		if ("clientX" in e && typeof e.clientX === "number") return e.clientX;
		if ("touches" in e && e.touches && e.touches[0])
			return e.touches[0].clientX;
		if ("changedTouches" in e && e.changedTouches && e.changedTouches[0])
			return e.changedTouches[0].clientX;
		return null;
	}

	function resetHandle(animated: boolean = false): void {
		if (!handle || !fill) return;

		const leftPx: number = PADDING;
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
			setTimeout((): void => {
				if (!handle || !fill) return;
				handle.style.transition = "";
				fill.style.transition = "";
			}, 300);
		}
	}

	function completeAndOpenQr(): void {
		if (!track || !handle || !fill) return;

		const rect: DOMRect = track.getBoundingClientRect();
		const maxLeft: number = rect.width - handleWidth - PADDING;
		handle.style.transition = "left 120ms linear";
		fill.style.transition = "width 120ms linear";
		handle.style.left = maxLeft + "px";
		fill.style.width = "100%";
		// 完了時は既存のモーダルを使う（もし残っている場合）
		setTimeout((): void => {
			if (overlay) {
				overlay.classList.add("show");
				overlay.setAttribute("aria-hidden", "false");
				document.body.style.overflow = "hidden";
				const firstFocusable = overlay.querySelector(
					".qr-close",
				) as HTMLElement | null;
				firstFocusable?.focus();
			} else {
				// フォールバック: 別ページへ遷移
				location.href = "Coupon-QR.html";
			}
		}, 160);
	}

	function openQrPage(): void {
		// クリック時のフォールバック遷移
		location.href = "Coupon-QR. html";
	}

	function closeQr(): void {
		if (!overlay) return;
		overlay.classList.remove("show");
		overlay.setAttribute("aria-hidden", "true");
		document.body.style.overflow = "";
		resetHandle(true);
	}

	// イベントハンドラ
	if (closeBtn) {
		closeBtn.addEventListener("click", closeQr);
	}

	if (handle) {
		// クリックイベント（ドラッグしていない場合のみ遷移）
		let clickStartTime: number = 0;
		let clickStartX: number = 0;

		handle.addEventListener("mousedown", (e: MouseEvent): void => {
			clickStartTime = Date.now();
			clickStartX = e.clientX;
		});

		handle.addEventListener("click", (e: MouseEvent): void => {
			const timeDiff: number = Date.now() - clickStartTime;
			const xDiff: number = Math.abs(e.clientX - clickStartX);

			// ドラッグでない場合（短時間 & 移動が少ない）
			if (timeDiff < 200 && xDiff < 5 && !dragging) {
				openQrPage();
			}
		});
	}

	// 初期化
	resetHandle(false);
}

export {};
