// 最低限のUIロジック：投稿作成、localStorageによる簡易永続化
// 「いいね・コメント」機能は削除済み

(function () {
	// Utilities
	const q = s => document.querySelector(s);
	const qa = s => Array.from(document.querySelectorAll(s));

	const feedEl = q("#feed");
	const template = q("#post-template");
	const storageKey = "scenetrip_demo_posts_v1";

	// サンプルユーザー情報（変更可）
	const currentUser = {
		name: "あなた",
		avatar: "Profile_pic.JPG",
	};

	// 初期化：既存投稿読み込み（localStorage）
	let posts = loadPosts();

	// DOM要素
	const composerText = q("#composer-text");
	const composerImage = q("#composer-image");
	const composerPreview = q("#composer-preview");
	const postBtn = q("#post-btn");

	// 画像プレビュー処理
	composerImage.addEventListener("change", e => {
		const file = e.target.files[0];
		if (!file) {
			composerPreview.innerHTML = "";
			composerPreview.style.display = "none";
			return;
		}
		const reader = new FileReader();
		reader.onload = () => {
			composerPreview.style.display = "block";
			composerPreview.innerHTML = `<img src="${reader.result}" alt="preview">`;
		};
		reader.readAsDataURL(file);
	});

	// 自動リサイズ
	composerText.addEventListener("input", e => {
		e.target.style.height = "auto";
		e.target.style.height = e.target.scrollHeight + "px";
	});

	// 投稿ボタン
	postBtn.addEventListener("click", async () => {
		const text = composerText.value.trim();
		const file = composerImage.files[0];
		let imgData = "";
		if (file) {
			imgData = await readFileAsDataURL(file);
		}
		if (!text && !imgData) {
			alert("テキストか画像を追加してください。");
			return;
		}
		const post = {
			id: "p_" + Date.now(),
			author: currentUser.name,
			avatar: currentUser.avatar,
			time: new Date().toISOString(),
			text,
			image: imgData,
		};
		posts.unshift(post);
		savePosts();
		renderFeed();
		// リセット
		composerText.value = "";
		composerImage.value = "";
		composerPreview.innerHTML = "";
		composerPreview.style.display = "none";
		composerText.style.height = "auto";
	});

	// レンダリング
	function renderFeed() {
		feedEl.innerHTML = "";
		if (posts.length === 0) {
			const empty = document.createElement("div");
			empty.className = "empty";
			empty.textContent = "まだ投稿がありません。さっそく投稿してみましょう！";
			feedEl.appendChild(empty);
			return;
		}
		posts.forEach(post => {
			const frag = template.content.cloneNode(true);
			const article = frag.querySelector(".post-card");
			frag.querySelector(".post-avatar").src = post.avatar;
			frag.querySelector(".post-author").textContent = post.author;
			frag.querySelector(".post-time").textContent = timeAgo(
				new Date(post.time),
			);
			frag.querySelector(".post-body").textContent = post.text || "";
			const imgEl = frag.querySelector(".post-image");
			if (post.image) {
				imgEl.src = post.image;
				imgEl.style.display = "block";
			} else {
				imgEl.style.display = "none";
			}

			// ここではシェアボタンのみ（いいね・コメント機能は削除）
			const shareBtn = frag.querySelector(".btn-share");
			if (shareBtn) {
				shareBtn.addEventListener("click", () => {
					// 簡易シェア（URLコピー等の拡張はここに）
					navigator.clipboard?.writeText(location.href).then(
						() => alert("ページURLをコピーしました（簡易シェア）。"),
						() => alert("クリップボードへのコピーに失敗しました。"),
					);
				});
			}

			feedEl.appendChild(frag);
		});
	}

	// 保存・読み込み
	function savePosts() {
		try {
			localStorage.setItem(storageKey, JSON.stringify(posts));
		} catch (e) {}
	}
	function loadPosts() {
		try {
			const raw = localStorage.getItem(storageKey);
			if (!raw) return samplePosts();
			return JSON.parse(raw);
		} catch (e) {
			return samplePosts();
		}
	}

	// サンプル投稿（最初の表示用）
	function samplePosts() {
		return [
			{
				id: "p_sample_1",
				author: "はりまや",
				avatar: "Harimaya_Bridge.jpg",
				time: new Date(Date.now() - 1000 * 60 * 60).toISOString(),
				text: "先日行ったカフェが最高だった！窓際の席でゆっくりできます ☕️",
				image: "",
			},
			{
				id: "p_sample_2",
				author: "南風",
				avatar: "Harimaya_Bridge.jpg",
				time: new Date(Date.now() - 1000 * 60 * 60 * 24).toISOString(),
				text: "ここの町並みがすごく綺麗でした。おすすめです。",
				image: "",
			},
		];
	}

	// ヘルパー
	function readFileAsDataURL(file) {
		return new Promise((res, rej) => {
			const r = new FileReader();
			r.onload = () => res(r.result);
			r.onerror = rej;
			r.readAsDataURL(file);
		});
	}
	function timeAgo(date) {
		const s = Math.floor((Date.now() - date.getTime()) / 1000);
		if (s < 60) return `${s}秒前`;
		if (s < 3600) return `${Math.floor(s / 60)}分前`;
		if (s < 86400) return `${Math.floor(s / 3600)}時間前`;
		return `${Math.floor(s / 86400)}日前`;
	}
	function escapeHtml(s) {
		return String(s).replace(
			/[&<>"']/g,
			c =>
				({"&": "&amp;", "<": "&lt;", ">": "&gt;", '"': "&quot;", "'": "&#39;"})[
					c
				],
		);
	}

	// 初回レンダー
	renderFeed();

	// 画面ロード時の補助（フォーカスなど）
	window.addEventListener("load", () => {
		// composerに軽いヒントなど（必要ならここに追加）
	});
})();