// 最低限のUIロジック：投稿作成、localStorageによる簡易永続化

interface User {
	name: string;
	avatar: string;
}

interface Post {
	id:  string;
	author: string;
	avatar: string;
	time: string;
	text: string;
	image: string | null;
}

(function (): void {
	// Utilities
	const q = (s: string): HTMLElement | null => document.querySelector(s);
	const qa = (s: string): HTMLElement[] => Array.from(document.querySelectorAll(s));

	const feedEl = q("#feed") as HTMLElement;
	const template = q("#post-template") as HTMLTemplateElement;
	const storageKey = "scenetrip_demo_posts_v1";

	// サンプルユーザー情報（変更可）
	const currentUser: User = {
		name: "あなた",
		avatar: "Profile_pic.JPG",
	};

	// 初期化：既存投稿読み込み（localStorage）
	let posts:  Post[] = loadPosts();

	// DOM要素
	const composerText = q("#composer-text") as HTMLTextAreaElement;
	const composerImage = q("#composer-image") as HTMLInputElement;
	const composerPreview = q("#composer-preview") as HTMLImageElement;
	const postBtn = q("#post-btn") as HTMLButtonElement;

	// レンダリング
	function renderFeed(): void {
		if (! feedEl) return;

		feedEl.innerHTML = "";
		if (posts.length === 0) {
			const empty = document.createElement("div");
			empty.className = "empty";
			empty.textContent = "まだ投稿がありません。さっそく投稿してみましょう！";
			feedEl.appendChild(empty);
			return;
		}
		posts.forEach((post: Post) => {
			if (! template) return;

			const frag = template.content. cloneNode(true) as DocumentFragment;
			const article = frag.querySelector(". post-card") as HTMLElement;
			(frag.querySelector(".post-avatar") as HTMLImageElement).src = post.avatar;
			(frag.querySelector(". post-author") as HTMLElement).textContent = post.author;
			(frag.querySelector(".post-time") as HTMLElement).textContent = timeAgo(
				new Date(post.time),
			);
			(frag.querySelector(".post-body") as HTMLElement).textContent = post.text || "";
			const imgEl = frag.querySelector(".post-image") as HTMLImageElement;
			if (post.image) {
				imgEl.src = post. image;
				imgEl.style.display = "block";
			} else {
				imgEl. style.display = "none";
			}

			feedEl.appendChild(frag);
		});
	}

	// 保存・読み込み
	function savePosts(): void {
		try {
			localStorage.setItem(storageKey, JSON. stringify(posts));
		} catch (e) {
			console.error("Failed to save posts:", e);
		}
	}

	function loadPosts(): Post[] {
		try {
			const raw = localStorage.getItem(storageKey);
			if (!raw) return samplePosts();
			return JSON.parse(raw) as Post[];
		} catch (e) {
			console.error("Failed to load posts:", e);
			return samplePosts();
		}
	}

	// サンプル投稿（最初の表示用）
	function samplePosts(): Post[] {
		return /\/post\. html$/.test(location.pathname)
			? [
					{
						id: "p_sample_1",
						author: "はりまや",
						avatar: "Harimaya_Bridge.jpg",
						time: new Date(Date.now() - 1000 * 60 * 60).toISOString(),
						text: "最高だった！窓際の席でゆっくりできます ☕️",
						image:  null,
					},
					{
						id:  "p_sample_2",
						author: "bob",
						avatar: "Profile_pic.JPG",
						time: new Date(Date.now() - 1000 * 60 * 120).toISOString(),
						text: "ここのパンケーキ絶品です🥞",
						image: null,
					},
			  ]
			: [];
	}

	// 相対時刻表示
	function timeAgo(date: Date): string {
		const now = new Date();
		const diffMs = now.getTime() - date.getTime();
		const diffMin = Math.floor(diffMs / (1000 * 60));
		const diffHr = Math. floor(diffMs / (1000 * 60 * 60));
		const diffDay = Math.floor(diffMs / (1000 * 60 * 60 * 24));

		if (diffMin < 1) return "たった今";
		if (diffMin < 60) return `${diffMin}分前`;
		if (diffHr < 24) return `${diffHr}時間前`;
		return `${diffDay}日前`;
	}

	// 初期レンダリング
	renderFeed();
})();
