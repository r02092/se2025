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
				input: ["resources/css/app.css", "resources/ts/app.ts"],
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
