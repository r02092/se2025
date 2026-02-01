import addAddrHandler from "./addr";
import addImageHandler from "./profile_icon_handler";

addAddrHandler(
	document.getElementById("post_code") as HTMLInputElement,
	document.getElementById("pc2addrbtn") as HTMLButtonElement,
	document.getElementById("pref_select") as HTMLSelectElement,
	document.getElementById("city_select") as HTMLSelectElement,
	document.getElementById("address") as HTMLInputElement,
);

addImageHandler(
	document.getElementById("avatar_img") as HTMLInputElement,
	document.getElementById("profile_preview") as HTMLImageElement,
);
