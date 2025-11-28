import js from "@eslint/js";
import tseslint from "typescript-eslint";
import json from "@eslint/json";
import markdown from "@eslint/markdown";
import {defineConfig, globalIgnores} from "eslint/config";

export default defineConfig([
	{
		files: ["**/*.{js,ts}"],
		plugins: {js},
		extends: ["js/recommended"],
	},
	tseslint.configs.recommended,
	{
		languageOptions: {
			parserOptions: {
				projectService: {
					allowDefaultProject: ["eslint.config.ts", "vite.config.ts"],
				},
				tsconfigRootDir: import.meta.dirname,
			},
		},
	},
	{
		files: ["**/*.json"],
		plugins: {json},
		language: "json/json",
		extends: ["json/recommended"],
	},
	{
		files: ["**/*.json5"],
		plugins: {json},
		language: "json/json5",
		extends: ["json/recommended"],
	},
	{
		files: ["**/*.md"],
		plugins: {markdown},
		language: "markdown/gfm",
		extends: ["markdown/recommended"],
	},
	globalIgnores(["gas/dist/main.js"]),
]);
