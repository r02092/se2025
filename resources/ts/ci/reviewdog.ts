import childProcess from "child_process";
import fs from "fs";
import {ESLint} from "eslint";
import textlint from "textlint";
import {Stream} from "stream";
import which from "which";
type RdJsonDiagnostic = {
	message: string;
	location: {
		path: string;
		range: {
			start: {line: number; column?: number};
			end?: {line: number; column?: number};
		};
	};
	severity: string;
	code: {value: string | null; url: string};
	original_output: string;
};
(async () => {
	const gitDiff = childProcess.spawn(
		"git",
		[
			"diff",
			"--name-only",
			...(process.env.GITHUB_ACTIONS ? [process.argv[2]] : []),
		],
		{
			stdio: ["ignore", "pipe", "inherit"],
		},
	);
	const diff = await new Promise<string[]>(resolve => {
		gitDiff.stdout.on("data", data => {
			resolve(
				data
					.toString()
					.split("\n")
					.filter((f: string) => fs.existsSync(f)),
			);
		});
	});
	const containerArgs = ["exec", "-i", "se2025_app_1"];
	const eslintLinter = new ESLint();
	const diagnostics = JSON.parse(
		await (
			await eslintLinter.loadFormatter("eslint-formatter-rdjson")
		).format(await eslintLinter.lintFiles(diff)),
	).diagnostics.filter((d: RdJsonDiagnostic) => d.code.value !== null);
	const textlintTargets = diff.filter(
		f => f.endsWith(".md") || f.endsWith(".tex"),
	);
	if (textlintTargets.length) {
		const textlintResults = await textlint
			.createLinter({
				descriptor: await textlint.loadTextlintrc(),
			})
			.lintFiles(textlintTargets);
		for (const i of textlintResults) {
			for (const j of i.messages) {
				diagnostics.push({
					message: j.message,
					location: {
						path: i.filePath,
						range: j.loc,
					},
					severity: ["none", "warning", "error", "info"][j.severity],
					code: {
						value: j.ruleId,
						url: "https://textlint.org/",
					},
					original_output: JSON.stringify(j),
				});
			}
		}
	}
	const chktexTargets = diff.filter(f => f.endsWith(".tex"));
	if (chktexTargets.length && which.sync("chktex", {nothrow: true})) {
		(
			await getStdout(
				childProcess.spawn(
					"chktex",
					["-l", "docs/.chktexrc", "-v0", ...chktexTargets],
					{
						stdio: ["inherit", "pipe", "inherit"],
					},
				),
			)
		)
			.split("\n")
			.forEach(l => {
				const m = l.match(/^(.*):(\d+):(\d+):(\d+):(.*)$/);
				if (m) {
					diagnostics.push({
						message: m[5],
						location: {
							path: m[1],
							range: {
								start: {line: parseInt(m[2]), column: parseInt(m[3])},
							},
						},
						severity: "Warning",
						code: {
							value: m[4],
							url: "https://www.nongnu.org/chktex/",
						},
					});
				}
			});
	}
	const phpstan = childProcess.spawn(
		process.env.GITHUB_ACTIONS ? "vendor/bin/phpstan" : "podman",
		(process.env.GITHUB_ACTIONS
			? []
			: containerArgs.concat("vendor/bin/phpstan")
		).concat(["analyse", ...diff, "--error-format=raw", "--no-progress"]),
		{stdio: ["pipe", "pipe", "inherit"]},
	);
	let dataCnt = 0;
	phpstan.stdout.on("data", data => {
		dataCnt++;
		const PsDiagnostics: RdJsonDiagnostic[] = [];
		for (const l of data.toString().split("\n")) {
			const m = l.match(/^(.*\.php):(\d+):(.*)$/);
			if (m)
				PsDiagnostics.push({
					message: m[3],
					location: {
						path: m[1],
						range: {
							start: {line: parseInt(m[2])},
						},
					},
					severity: "ERROR",
					code: {value: null, url: ""},
					original_output: l,
				});
		}
		diagnostics.push(...PsDiagnostics);
		dataCnt--;
	});
	await new Promise<void>(resolve => {
		phpstan.on("exit", () => {
			resolve();
		});
	});
	while (dataCnt) await new Promise(r => setTimeout(r, 99));
	const reviewdog = childProcess.spawn(
		process.env.GITHUB_ACTIONS
			? "./reviewdog"
			: process.env.CONTAINER_TOOL || "podman",
		process.env.GITHUB_ACTIONS
			? ["-f=rdjson", "-reporter=github-pr-review", "-fail-on-error"]
			: containerArgs.concat([
					"reviewdog",
					"-f=rdjson",
					"-diff=git diff" +
						(process.env.GITHUB_ACTIONS ? " " + process.argv[2] : ""),
				]),
		{
			...(process.env.GITHUB_ACTIONS
				? {env: {...process.env, REVIEWDOG_GITHUB_API_TOKEN: process.argv[3]}}
				: {}),
			stdio: ["pipe", "inherit", "inherit"],
		},
	);
	reviewdog.stdin.write(
		JSON.stringify({
			diagnostics: await Promise.all(
				diagnostics.map(async (d: RdJsonDiagnostic) => {
					if (!/[ぁ-ヿ]/.test(d.message)) {
						d.message +=
							(process.env.GITHUB_ACTIONS
								? "\n**日本語訳**: "
								: "\n\x1b[31m\x1b[47m日本語訳\x1b[0m: ") +
							(await (
								await fetch(
									"https://script.google.com/macros/s/AKfycbyZqnoj6TCLql9jHJAFnJvGeh1wVjd-N67fRVTu6nxAJ2muLTEI9E8xn_1JNLbzK8DF/exec?t=" +
										encodeURIComponent(d.message),
								)
							).text());
					}
					d.location.path = d.location.path
						.replace("mnt/repo/", "")
						.replace(process.cwd(), "")
						.replace(/\\/g, "/")
						.replace(/^\//, "");
					d.original_output =
						"\x1b[1m" +
						d.location.path +
						":" +
						d.location.range.start.line +
						(d.location.range.start.column
							? ":" + d.location.range.start.column
							: "") +
						"\x1b[0m\n\x1b[33m" +
						d.severity.padEnd(9, " ") +
						"\x1b[0m " +
						d.message;
					return d;
				}),
			),
		}),
	);
	reviewdog.stdin.end();
})();
async function getStdout(
	cp: childProcess.ChildProcessByStdio<null, Stream.Readable, null>,
) {
	let out = "";
	cp.stdout.on("data", data => {
		out += data.toString();
	});
	return new Promise<string>(resolve => {
		cp.on("exit", () => {
			resolve(out);
		});
	});
}
