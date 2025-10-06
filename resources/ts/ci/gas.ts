function doGet(
	e: GoogleAppsScript.Events.DoGet,
): GoogleAppsScript.Content.TextOutput {
	return ContentService.createTextOutput(
		LanguageApp.translate(e.parameter.t, "en", "ja"),
	);
}
void doGet.bind.bind; // 削除対策
