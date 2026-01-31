import addSuggestHandler from "./add_suggest_handler";

for (const i of document.querySelectorAll("[id^='cond_']")) {
	addSuggestHandler(i as HTMLInputElement);
}
