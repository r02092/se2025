import * as QRCode from "qrcode";

const yesBtn = document.getElementById("yes_btn") as HTMLButtonElement;
yesBtn.addEventListener("click", showCoupon);
if (Number(yesBtn.dataset.active)) showCoupon();

async function showCoupon() {
	const match = location.pathname.match(/^\/coupon\/(\d+)$/);
	if (match) {
		const body = new FormData();
		body.set("coupon_id", match[1]);
		const res = await (
			await fetch("api", {
				method: "POST",
				headers: {
					"X-CSRF-TOKEN": (
						document.querySelector('meta[name="csrf-token"]') as HTMLMetaElement
					).content,
				},
				body: body,
			})
		).json();
		if (res.key) {
			(
				document.getElementById("coupon_overlay") as HTMLDivElement
			).style.display = "flex";
			const imgElem = document.getElementById("coupon_qr") as HTMLImageElement;
			imgElem.src = await QRCode.toDataURL("scenetrip:coupon/" + res.key, {
				scale: 1,
			});
		} else {
			alert(res.message);
			(
				document.getElementById("coupon_confirm_overlay") as HTMLDivElement
			).style.display = "none";
		}
	}
}
