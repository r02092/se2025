import QrScanner from 'qr-scanner'; // QrScannerライブラリをインポート

console.log("funpage_checkin.ts: loaded");

// 要素を探す（候補リスト）
const overlayCandidates = ["checkin-qr-overlay", "qr-overlay", "checkin-overlay"];
let overlay: HTMLElement | null = null;

for (const id of overlayCandidates) {
    const el = document.getElementById(id);
    if (el) { overlay = el; break; }
}

if (!overlay) {
    console.error("オーバーレイ要素が見つかりません。");
} else {
    const video = document.getElementById('qr-video') as HTMLVideoElement;
    const statusMessage = document.getElementById('scanner-status');

    // --- QRスキャナーの初期化 ---
    const qrScanner = new QrScanner(
        video,
        async (result) => {
            qrScanner.stop(); // 読み取ったら一旦カメラ停止
            if (statusMessage) statusMessage.textContent = 'チェックイン中...';
            await handleCheckin(result.data); // サーバーへ送信
        },
        { returnDetailedScanResult: true, highlightScanRegion: true }
    );

    function showOverlay(): void {
        if (!overlay) return;
        overlay.classList.add("show");
        overlay.style.opacity = "1"; // 見えるようにする
        
        // オーバーレイが表示されたらカメラを起動
        qrScanner.start().then(() => {
            if (statusMessage) statusMessage.textContent = 'QRコードを枠内に収めてください';
        }).catch(err => {
            if (statusMessage) statusMessage.textContent = 'カメラの起動に失敗しました。';
        });
    }

    async function handleCheckin(stampKey: string) {
        // 現在地を取得
        navigator.geolocation.getCurrentPosition(async (pos) => {
            const response = await fetch('/api/checkin', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': (document.querySelector('meta[name="csrf-token"]') as HTMLMetaElement).content
                },
                body: JSON.stringify({
                    stamp_key: stampKey,
                    lat: pos.coords.latitude,
                    lng: pos.coords.longitude
                })
            });
            const data = await response.json();
            alert(data.message || data.error);
            window.location.href = '/funpage'; // 実績画面に戻る
        }, (err) => {
            alert("位置情報の取得を許可してください。");
            qrScanner.start();
        });
    }

    // 自動表示
    setTimeout(showOverlay, 120);
}

export {};