import maplibregl from "maplibre-gl";
import "maplibre-gl/dist/maplibre-gl.css";

// 時間差分を無事列として取得
function getDiffTimeFormat(timestamp: number): string {
	const date = new Date(timestamp * 1000);
	const seconds = Math.floor(
		(new Date().getTime() - new Date(date).getTime()) / 1000,
	);

	// 期間と単位の定義
	const intervals = [
		{label: "year", seconds: 31536000},
		{label: "month", seconds: 2592000},
		{label: "day", seconds: 86400},
		{label: "hour", seconds: 3600},
		{label: "minute", seconds: 60},
	];

	// ブラウザの言語を自動取得してフォーマッターを作成
	const rtf = new Intl.RelativeTimeFormat(navigator.language, {
		numeric: "auto",
	});

	for (const interval of intervals) {
		const count = Math.floor(seconds / interval.seconds);
		if (count >= 1) {
			return rtf.format(-count, interval.label as Intl.RelativeTimeFormatUnit);
		}
	}
	return "たった今";
}

let currentMarkers: Array<maplibregl.Marker> = [];

// マップの表示範囲を基に投稿内容をリロード
async function reloadPosts(): Promise<void> {
	// 現在の表示範囲（北東と南西の座標）を取得
	const bounds = map.getBounds();
	const sw = bounds.getSouthWest();
	const ne = bounds.getNorthEast();

	// 範囲をパラメータとしてfetch送信
	const response = await fetch(
		`/post/load?sw_lat=${sw.lat}&sw_lng=${sw.lng}&ne_lat=${ne.lat}&ne_lng=${ne.lng}`,
	);
	const posts: Array<{
		username: string;
		avatar_url: string;
		photo_img_url: string;
		comment: string;
		lng: number;
		lat: number;
		created_at: number;
		updated_at: number;
	}> = await response.json();

	// 古いマーカーを地図から削除
	currentMarkers.forEach(marker => marker.remove());
	currentMarkers = [];

	// 新しいスポットをループしてマーカーを設置
	posts.forEach(post => {
		const popup = new maplibregl.Popup({offset: 25}).setHTML(`
				<header class="post-head">
					<img class="post-avatar" src="${post.avatar_url}" />
					<div class="post-meta">
						<div class="post-author">${post.username}</div>
						<div class="post-time">${getDiffTimeFormat(post.created_at)}</div>
					</div>
				</header>
				<img class="post-image" src="${post.photo_img_url}" />
				<div class="post-body">${post.comment}</div>
			`);

		const marker = new maplibregl.Marker()
			.setLngLat([post.lng, post.lat])
			.setPopup(popup) // ポップアップを紐付け
			.addTo(map);

		currentMarkers.push(marker); // 配列に保存（次回の削除用）
	});
}

// マップ生成
const map = new maplibregl.Map({
	container: "map",
	style: "https://tile.openstreetmap.jp/styles/osm-bright-ja/style.json",
	center: [133.685047, 33.607133], // [経度, 緯度] 四国周辺
	zoom: 13,
});
map.addControl(new maplibregl.NavigationControl(), "top-right");

// コンテンツが読み込まれた後に投稿データ取得
document.addEventListener("DOMContentLoaded", () => {
	reloadPosts();
});

// 地図の移動やズームが終わった時に投稿データ取得
map.on("moveend", async () => {
	reloadPosts();
});
