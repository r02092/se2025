const fileInput = document.getElementById(
	"avatar-img",
) as HTMLInputElement | null;
const previewImage = document.getElementById(
	"profile-preview",
) as HTMLImageElement | null;

if (fileInput && previewImage) {
	fileInput.addEventListener("change", function (event: Event) {
		const target = event.target as HTMLInputElement;
		const file = target.files ? target.files[0] : null;

		if (!file) return;

		// 2MB limit (bytes)
		const LIMIT = 2 * 1024 * 1024;

		if (file.size <= LIMIT) {
			// No compression needed, just preview
			const reader = new FileReader();
			reader.onload = function (e: ProgressEvent<FileReader>) {
				if (
					e.target &&
					e.target.result &&
					typeof e.target.result === "string"
				) {
					previewImage.src = e.target.result;
				}
			};
			reader.readAsDataURL(file);
			return;
		}

		// Compression needed
		const reader = new FileReader();
		reader.readAsDataURL(file);
		reader.onload = function (event: ProgressEvent<FileReader>) {
			if (
				!event.target ||
				!event.target.result ||
				typeof event.target.result !== "string"
			)
				return;

			const img = new Image();
			img.src = event.target.result;
			img.onload = function () {
				const canvas = document.createElement("canvas");
				const ctx = canvas.getContext("2d");

				if (!ctx) return;

				// Resize logic (max dimension 1024px)
				const MAX_WIDTH = 1024;
				const MAX_HEIGHT = 1024;
				let width = img.width;
				let height = img.height;

				if (width > height) {
					if (width > MAX_WIDTH) {
						height *= MAX_WIDTH / width;
						width = MAX_WIDTH;
					}
				} else {
					if (height > MAX_HEIGHT) {
						width *= MAX_HEIGHT / height;
						height = MAX_HEIGHT;
					}
				}

				canvas.width = width;
				canvas.height = height;
				ctx.drawImage(img, 0, 0, width, height);

				// Compress to JPEG with 0.8 quality
				canvas.toBlob(
					function (blob) {
						if (!blob) {
							alert("画像の圧縮に失敗しました。");
							return;
						}

						if (blob.size > LIMIT) {
							alert(
								"画像サイズが大きすぎます。自動圧縮しても2MB以下になりませんでした。別の画像を選択してください。",
							);
							fileInput.value = ""; // Clear
							return;
						}

						// Create new File object
						const compressedFile = new File([blob], file.name, {
							type: "image/jpeg",
							lastModified: Date.now(),
						});

						// Replace file input files
						const dataTransfer = new DataTransfer();
						dataTransfer.items.add(compressedFile);
						fileInput.files = dataTransfer.files;

						// Update preview with compressed image
						previewImage.src = URL.createObjectURL(compressedFile);

						console.log(
							"Image compressed:",
							file.size,
							"->",
							compressedFile.size,
						);
					},
					"image/jpeg",
					0.8,
				);
			};
		};
	});
}
