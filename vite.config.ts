import {defineConfig} from "vite";
import laravel from "laravel-vite-plugin";
import fs from "fs";

export default defineConfig(({mode}) => {
	return {
		server: {
			hmr: {
				host: "localhost",
			},
		},
		plugins: [
			laravel({
				input: [
					"resources/css/app.css",
					"resources/ts/app.ts",
					// 個別TS modules - app.ts経由で動的import
					"resources/ts/home.ts",
					"resources/ts/photo.ts",
					"resources/ts/coupon.ts",
					"resources/ts/coupon_qr.ts",
					"resources/ts/coupon_selected.ts",
					"resources/ts/funpage_checkin.ts",
				],
				refresh: true,
			}),
			{
				name: "copy-appsscript-json",
				writeBundle() {
					if (mode === "gas") {
						fs.mkdirSync("gas/dist", {recursive: true});
						fs.copyFileSync("gas/appsscript.json", "gas/dist/appsscript.json");
					}
				},
			},
		],
		build:
			mode === "gas"
				? {
						rollupOptions: {
							input: "resources/ts/ci/gas.ts",
							output: {
								dir: "gas/dist",
								entryFileNames: "main.js",
								preserveModules: true,
							},
							preserveEntrySignatures: "strict",
						},
						minify: false,
					}
				: undefined,
	};
});
