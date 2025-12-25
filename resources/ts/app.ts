// Route-based dynamic imports for page-specific TypeScript modules
document.addEventListener("DOMContentLoaded", (): void => {
	const pathname = window.location.pathname;

	// Post page
	if (pathname.includes("/post")) {
		import("./post");
	}

	// Coupon list page
	if (pathname.includes("/coupon") && !pathname.includes("/coupon-qr")) {
		import("./coupon");
	}

	// Coupon QR detail page
	if (pathname.includes("/coupon-qr")) {
		import("./coupon-QR");
	}

	// Coupon selected / slider page
	if (pathname.includes("/coupon-selected")) {
		import("./coupon-selected");
	}

	// Funpage checkin with QR
	if (pathname.includes("/funpage") || pathname.includes("/checkin")) {
		import("./funpage-checkin");
	}
});
