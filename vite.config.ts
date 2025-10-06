import {defineConfig} from "vite";
import fs from "fs";

export default defineConfig(() => {
	return {
		server: {
			hmr: {
				host: "localhost",
			},
		},
		plugins: [
			{
				name: "copy-appsscript-json",
				writeBundle() {
					fs.mkdirSync("gas/dist", {recursive: true});
					fs.copyFileSync("gas/appsscript.json", "gas/dist/appsscript.json");
				},
			},
		],
		build: {
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
		},
	};
});
