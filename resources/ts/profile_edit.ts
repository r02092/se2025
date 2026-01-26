import addImageHandler from "./profile_icon_handler";
addImageHandler(
	document.getElementById("avatar_img") as HTMLInputElement,
	document.getElementById("profile_preview") as HTMLImageElement,
);
