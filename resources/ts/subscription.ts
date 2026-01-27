import addAddrHandler from "./addr";

addAddrHandler(
	document.getElementById("post_code") as HTMLInputElement,
	document.getElementById("pc2addrbtn") as HTMLButtonElement,
	document.getElementById("pref_select") as HTMLSelectElement,
	document.getElementById("city_select") as HTMLSelectElement,
	document.getElementById("address") as HTMLInputElement,
);
