import QrScanner from "qr-scanner"; // QrScannerライブラリをインポート

console.log("funpage_checkin.ts: loaded");

// 要素を探す（候補リスト）
const overlayCandidates = [
	"checkin_qr_overlay",
	"qr_overlay",
	"checkin_overlay",
];
let overlay: HTMLElement | null = null;

for (const id of overlayCandidates) {
	const el = document.getElementById(id);
	if (el) {
		overlay = el;
		break;
	}
}

if (!overlay) {
	console.error("オーバーレイ要素が見つかりません。");
} else {
	const video = document.getElementById("qr_video") as HTMLVideoElement;
	const statusMessage = document.getElementById(
		"scanner_status",
	) as HTMLParagraphElement;

	// --- QRスキャナーの初期化 ---
	const qrScanner = new QrScanner(
		video,
		async result => {
			qrScanner.stop(); // 読み取ったら一旦カメラ停止
			const match = result.data.match(/^scenetrip:(coupon|stamp)\/(\d+)$/);
			switch (match ? match[1] : "") {
				case "coupon":
					statusMessage.textContent = "クーポンを読み込み中……";
					await handleCoupon((match as RegExpMatchArray)[2]);
					break;
				case "stamp":
					statusMessage.textContent = "チェックイン中……";
					await handleCheckin((match as RegExpMatchArray)[2]); // サーバーへ送信
					break;
				default:
					statusMessage.textContent =
						"チェックイン用の二次元コードではありません。";
			}
		},
		{returnDetailedScanResult: true, highlightScanRegion: true},
	);

	function showOverlay(): void {
		if (!overlay) return;
		overlay.classList.add("show");
		overlay.style.opacity = "1"; // 見えるようにする

		// オーバーレイが表示されたらカメラを起動
		qrScanner
			.start()
			.then(() => {
				if (statusMessage)
					statusMessage.textContent = "二次元コードを枠内に収めてください";
			})
			.catch(err => {
				if (statusMessage)
					statusMessage.textContent = "カメラの起動に失敗しました。";
			});
	}

	async function handleCoupon(couponKey: string) {
		const body = new FormData();
		body.set("key", couponKey);
		alert(
			await (
				await fetch("/business/coupon/api", {
					method: "POST",
					headers: {
						"X-CSRF-TOKEN": (
							document.querySelector(
								'meta[name="csrf-token"]',
							) as HTMLMetaElement
						).content,
					},
					body: body,
				})
			).text(),
		);
		location = "/funpage" as unknown as Location;
	}

	async function handleCheckin(stampKey: string) {
		// 現在地を取得
		navigator.geolocation.getCurrentPosition(
			async pos => {
				const response = await fetch("/funpage/checkin/api", {
					method: "POST",
					headers: {
						"Content-Type": "application/json",
						"X-CSRF-TOKEN": (
							document.querySelector(
								'meta[name="csrf-token"]',
							) as HTMLMetaElement
						).content,
					},
					body: JSON.stringify({
						stamp_key: stampKey,
						lat: pos.coords.latitude,
						lng: pos.coords.longitude,
					}),
				});
				const data = await response.json();
				alert(
					data.error ||
						data.message +
							(data.coupon_result?.success ? data.coupon_result.message : ""),
				);
				window.location.href = "/funpage"; // 実績画面に戻る
			},
			err => {
				alert("位置情報の取得を許可してください。");
				qrScanner.start();
			},
		);
	}

	// 自動表示
	setTimeout(showOverlay, 120);
}
