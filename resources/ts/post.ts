// 最低限のUIロジック：投稿作成、いいね、localStorageによる簡易永続化

// ===== Type Definitions =====
interface Comment {
	id: string;
	author: string;
	text: string;
	time: string;
}

interface PostItem {
	id: string | null;
	author: string;
	avatar: string;
	time: string;
	text: string | null;
	image: string | null;
	likes: number;
	liked: boolean;
	comments: Comment[];
}

interface CurrentUser {
	name: string;
	avatar: string;
}

((): void => {
	// Utilities
	const q = (s: string): HTMLElement | null => document.querySelector(s);
	const qa = (s: string): HTMLElement[] =>
		Array.from(document.querySelectorAll(s));

	const feedEl = q("#feed") as HTMLElement | null;
	const template = q("#post-template") as HTMLTemplateElement | null;
	const storageKey = "scenetrip_demo_posts_v1";

	// サンプルユーザー情報（変更可）
	const currentUser: CurrentUser = {
		name: "あなた",
		avatar: "Profile_pic.JPG",
	};

	// 初期化：既存投稿読み込み（localStorage）
	const posts: PostItem[] = loadPosts();

	// DOM要素
	const composerText = q("#composer-text") as HTMLTextAreaElement | null;
	const composerImage = q("#composer-image") as HTMLInputElement | null;
	const composerPreview = q("#composer-preview") as HTMLElement | null;
	const postBtn = q("#post-btn") as HTMLButtonElement | null;

	// レンダリング
	function renderFeed(): void {
		if (!feedEl) return;
		feedEl.innerHTML = "";
		if (posts.length === 0) {
			const empty = document.createElement("div");
			empty.className = "empty";
			empty.textContent = "まだ投稿がありません。さっそく投稿してみましょう！";
			feedEl.appendChild(empty);
			return;
		}
		posts.forEach((post: PostItem): void => {
			if (!template) return;
			const frag = template.content.cloneNode(true) as DocumentFragment;
			const avatarEl = frag.querySelector(
				".post-avatar",
			) as HTMLImageElement | null;
			const authorEl = frag.querySelector(".post-author") as HTMLElement | null;
			const timeEl = frag.querySelector(".post-time") as HTMLElement | null;
			const bodyEl = frag.querySelector(".post-body") as HTMLElement | null;
			const imgEl = frag.querySelector(
				".post-image",
			) as HTMLImageElement | null;

			if (avatarEl) avatarEl.src = post.avatar;
			if (authorEl) authorEl.textContent = post.author;
			if (timeEl) timeEl.textContent = timeAgo(new Date(post.time));
			if (bodyEl) bodyEl.textContent = post.text || "";
			if (imgEl) {
				if (post.image) {
					imgEl.src = post.image;
					imgEl.style.display = "block";
				} else {
					imgEl.style.display = "none";
				}
			}

			feedEl?.appendChild(frag);
		});
	}

	// 保存・読み込み
	function savePosts(): void {
		try {
			localStorage.setItem(storageKey, JSON.stringify(posts));
		} catch (e) {
			console.error("Failed to save posts:", e);
		}
	}
	function loadPosts(): PostItem[] {
		try {
			const raw = localStorage.getItem(storageKey);
			if (!raw) return samplePosts();
			return JSON.parse(raw);
		} catch (e) {
			return samplePosts();
		}
	}

	// サンプル投稿（最初の表示用）
	function samplePosts(): PostItem[] {
		return /\/post\.html$|\/post/.test(location.pathname)
			? [
					{
						id: "p_sample_1",
						author: "はりまや",
						avatar: "Harimaya_Bridge.jpg",
						time: new Date(Date.now() - 1000 * 60 * 60).toISOString(),
						text: "最高だった！窓際の席でゆっくりできます ☕️",
						image: null,
						likes: 3,
						liked: false,
						comments: [
							{
								id: "c1",
								author: "alice",
								text: "写真見たい！",
								time: new Date(Date.now() - 1000 * 60 * 30).toISOString(),
							},
						],
					},
					{
						id: "p_sample_2",
						author: "南風",
						avatar: "Profile_nanpu.jpg",
						time: new Date(Date.now() - 1000 * 60 * 60 * 24).toISOString(),
						text: "ここの町並みがすごく綺麗でした。おすすめです。",
						image: "post-station.jpg",
						likes: 1,
						liked: false,
						comments: [],
					},
				]
			: [
					{
						id: null,
						author: "ツル☆ハシ",
						avatar: "Profile_4.jpg",
						time: new Date(Date.now() - 1000 * 60 * 60).toISOString(),
						text: "ついに香美市に到着！あのゲームにも出てきた場所、土佐山田駅だ！",
						image: "post-station.jpg",
						likes: 0,
						liked: false,
						comments: [],
					},
				];
	}

	// ヘルパー
	function readFileAsDataURL(file: File): Promise<string | ArrayBuffer | null> {
		return new Promise(
			(
				res: (value: string | ArrayBuffer | null) => void,
				rej: (reason?: any) => void,
			) => {
				const r = new FileReader();
				r.onload = (): void => {
					res(r.result);
				};
				r.onerror = (): void => {
					rej(r.error);
				};
				r.readAsDataURL(file);
			},
		);
	}
	function timeAgo(date: Date): string {
		const s = Math.floor((Date.now() - date.getTime()) / 1000);
		if (s < 60) return `${s}秒前`;
		if (s < 3600) return `${Math.floor(s / 60)}分前`;
		if (s < 86400) return `${Math.floor(s / 3600)}時間前`;
		return `${Math.floor(s / 86400)}日前`;
	}
	function escapeHtml(s: any): string {
		const escapeMap: Record<string, string> = {
			"&": "&amp;",
			"<": "&lt;",
			">": "&gt;",
			'"': "&quot;",
			"'": "&#39;",
		};
		return String(s).replace(/[&<>"']/g, (c: string) => escapeMap[c] || c);
	}

	// 初回レンダー
	renderFeed();

	// 画面ロード時の補助（フォーカスなど）
	window.addEventListener("load", (): void => {
		// composerに軽いヒント
	});
})();

export {};
