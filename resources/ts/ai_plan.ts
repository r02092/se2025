document.addEventListener("DOMContentLoaded", async () => {
	// PHPからデータを取得（存在しない場合はnullになるように修正）
	const fromId = document.getElementById("ai-container")?.dataset.from;
	const toId = document.getElementById("ai-container")?.dataset.to;

	const csrfTokenMeta = document.querySelector(
		'meta[name="csrf-token"]',
	) as HTMLMetaElement;
	const csrfToken = csrfTokenMeta ? csrfTokenMeta.content : "";

	const loading = document.getElementById("ai-loading") as HTMLDivElement;
	const result = document.getElementById("ai-result") as HTMLDivElement;
	const errorArea = document.getElementById("ai-error") as HTMLDivElement;
	const textField = document.getElementById("ai-text") as HTMLDivElement;
	const spotsList = document.getElementById("ai-spots-list") as HTMLDivElement;

	// ▼▼▼ 指示文（プロンプト）の動的生成 ▼▼▼
	let promptText = "";
	if (fromId && toId) {
		// 両方ある場合（ルート検索）
		promptText = `
						出発地から目的地へのルート上で、おすすめの観光スポットや食事処を提案してください。
						リストの中から最適なものを選び、なぜそこが良いのか理由を含めてプランを提案してください。
					`;
	} else {
		// 片方だけの場合（周辺検索）
		promptText = `
						指定された地点の周辺にある、おすすめの観光スポットや食事処を提案してください。
						リストの中から最適なものを選び、その場所の魅力を伝えてください。
					`;
	}

	// 共通の制約を追加
	promptText += `
					【システム強制ルール】
					1. 回答の「1行目」は、選んだスポットの **IDのみ** をカンマ区切りで出力してください（例: 1,5）。
					2. 2行目以降に、推薦する理由を魅力的に書いてください。
				`;

	try {
		const response = await fetch("/ai-search", {
			method: "POST",
			headers: {
				"Content-Type": "application/json",
				"X-CSRF-TOKEN": csrfToken,
			},
			body: JSON.stringify({
				chat: promptText,
				from: fromId,
				to: toId,
			}),
		});

		const data = await response.json();

		if (!response.ok) {
			throw new Error(data.error || "通信エラーが発生しました");
		}

		// 画面更新
		loading.style.display = "none";
		result.style.display = "block";

		// テキスト表示 (Markdown簡易変換)
		const rawText = data.explanation || "解説文が取得できませんでした。";
		const formattedText = rawText
			.replace(/\*\*(.*?)\*\*/g, '<b style="color:#2563eb;">$1</b>')
			.replace(
				/\[(.*?)\]\(spots\/(\d+)\)/g,
				'<a href="/detail?id=$2" target="_blank" style="color:#2563eb; text-decoration:underline; font-weight:bold;">$1</a>',
			)
			.replace(/\n/g, "<br>");

		textField.innerHTML = formattedText;

		// スポットカード生成
		spotsList.innerHTML = "";
		if (data.recommended_spots && data.recommended_spots.length > 0) {
			data.recommended_spots.forEach(
				(spot: {
					id: number;
					name: string;
					type: number;
					postal_code: number;
					addr_city: number;
					addr_detail: string;
					img_ext: string;
				}) => {
					const html = `<a href="/detail?id=${spot.id}">
	<div>
		<img src="/images/${spot.name}.${spot.img_ext || "jpg"}"
			onerror="this.src='/images/Harimaya_Bridge.jpg'">
	</div>
	<div>
		<h4>${spot.name}</h4>
		<p>
			${spot.description || "説明文がありません"}
		</p>
	</div>
</a>`;
					spotsList.innerHTML += html;
				},
			);
		} else {
			spotsList.innerHTML =
				'<p style="color:#666; grid-column: 1/-1;">スポットデータはありませんでした。</p>';
		}
	} catch (e) {
		console.error(e);
		loading.style.display = "none";
		errorArea.style.display = "block";
		(
			document.getElementById("ai-error-msg") as HTMLParagraphElement
		).innerText = (e as unknown & {message: string}).message;
	}
});
