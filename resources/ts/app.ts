// Route-based dynamic imports for page-specific TypeScript modules
document.addEventListener("DOMContentLoaded", (): void => {
	const pathname = window.location.pathname;

	// Home page
	if (pathname === "/") {
		import("./home");
	}

	// Post page
	if (pathname === "/post") {
		import("./post");
	}

	// Coupon QR detail page (must check before general coupon check)
	if (/\/coupon\/\d+\/qr/.test(pathname)) {
		import("./coupon-QR");
	}
	// Coupon selected / detail page
	else if (/\/coupon\/\d+/.test(pathname)) {
		import("./coupon-selected");
	}
	// Coupon list page
	else if (pathname === "/coupon") {
		import("./coupon");
	}

	// Funpage checkin with QR
	if (pathname === "/funpage/checkin") {
		import("./funpage-checkin");
	}
});
