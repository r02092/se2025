# ソフトウェア工学用リポジトリ

文書とソフトウェアのソースコードを管理するリポジトリです。

品質を保つため、`main`ブランチには直接プッシュできないようにしており、Pull Requestを作成する必要があります。また、Pull Requestは後述する自動レビュなどが通らないとマージできないようにしています。

このファイル内の説明で示すコマンドは全てリポジトリのルートディレクトリで実行することを想定しています。

⚠️ 最初に[準備方法](#準備方法)に従ってソフトウェアのインストールなどの準備をおこなってから使用してください。

## ルール

ブランチを作成する際は`feature/<変更点を示す名前>`という名前にしてください。

## 主なディレクトリとファイル

### `.github`

リモートリポジトリの自動化に関するファイルが入っているディレクトリです。

`workflows`ディレクトリには、GitHub Actionsのワークフローが入っています。ワークフローに問題がある場合のみ変更しても構いません。Pull Requestのチェックが通らないときは、プログラムや文章自体を修正するほか、リンタのルールの変更で改善する場合がありますので、まずはそちらの変更を試してみてください。

`renovate.json5`は、[Renovate](#renovate)の設定ファイルです。こちらも必要に応じて変更してください。

### `.vscode`

Visual Studio Codeの設定ファイルです。設定を共有できるように置いています。

リポジトリで推奨する拡張機能の情報が含まれており、拡張機能の検索欄に`@recommended`と入れるとそれらを表示できます。

### `dev`

開発用環境に関するファイルを置く場所です。コンテナイメージの設計図となる`Dockerfile`が置かれています。

### `docs`

システム提案書や外部設計書、内部設計書などの文書を置く場所です。ここに置いたLaTeX形式のファイルは[後述する方法](#文書のコンパイル)でコンパイルできます。

`docs.cls`は`jlreq`ベースのドキュメントクラスで、文書の表紙を自動生成します。また、GitHub Actionsでのコンパイル時にフォントの変更やタグ名に基づいたバージョン番号の自動記入をおこないます。

### `eslint.config.ts`

ESLintの設定ファイルです。リンタの設定が厳しすぎると思ったら変更してください。

### `package.json`

pnpmの設定ファイルです。Prettierやtextlintの設定もここに含まれているので、これも必要に応じて変更してください。

## 使用方法

### 開発用コンテナ

<sub>※ Dockerを使用する場合は`podman`の部分を`docker`に読み替えてください。</sub>

下記のコマンドで開発用コンテナが起動します。

```sh
podman compose up -d
```

開発用コンテナのイメージは`dev/Dockerfile`で管理しています。これを更新した場合、下記のコマンドでイメージを削除し、上記のコマンドで再び起動し直すとイメージを更新できます。

```sh
podman compose down
podman rmi se2025_app
```

### 自動レビュ

下記のコマンドを実行することでリンタによる簡易的な自動レビュを実行できます。英語のメッセージは日本語訳も表示されるようにしていますが、機械翻訳のため、間違っていたり不自然だったりする場合があります。

```sh
pnpm run review
```

このレビュによる指摘が無くならないとPull Requestをマージできないため、指摘箇所の修正をおこなうか、リンタの設定を変更してルールを甘くするなどの対処をおこなう必要があります。

### 文書のコンパイル

コミットに以下の形式タグを付けるとGitHub上で文書がコンパイルされ、Releasesにコンパイル後のPDFファイルがアップロードされます。

`<拡張子を除いた文書のファイル名>/v<文書のバージョン>`（例:`teian/v0.1`）

⚠️ 提出するPDFファイルの作成は、原則この方法を使用しておこなってください。

### Renovate

renovateという名前のbotがPull Requestを開くことがあります。このbotは依存関係の更新をおこないます。もし自動レビュなどでエラーが発生した場合は、手動で修正してからマージしてください。エラー発生しなかった場合、メジャーアップデートなどの問題の起きやすい更新以外であれば自動でマージされますので、放置しておいても構いません。

## 準備方法

リポジトリの使用に必要なソフトウェアのインストール方法などを説明しています。既にインストール済みのものがある場合はそれを使用して構いません。

### Node.jsのインストール

`.nvmrc`ファイルで定めているバージョンのNode.jsをNVMを使ってインストールする手順です。それ以外のバージョンでも大きくバージョンが離れていなければおそらく使用できますが、定めているバージョンを使用することをおすすめします。

<details>
<summary>UNIX系OS（FreeBSD、Linux、macOSなど）の場合</summary>

まず、下記のコマンドでNVMをインストールします。

```sh
curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/master/install.sh | bash
```

それから、`PATH`を再読み込みするためにシェルを再起動した後、このリポジトリで下記のコマンドを実行してNode.jsをインストールします。

```sh
nvm install
nvm use
```

</details>
<details>
<summary>Windowsの場合</summary>

まず、[Releases · coreybutler/nvm-windows](https://github.com/coreybutler/nvm-windows/releases)からnvm-setup.exeをダウンロードし、実行することでNVM for Windowsをインストールします。

それから、下記のコマンドを実行してNode.jsをインストールします。

```ps1
nvm install $(Get-Content .nvmrc)
nvm use $(Get-Content .nvmrc)
```

</details>

### pnpmのインストールと依存関係の解決

下記のコマンドを実行してpnpmをインストールし、依存関係を解決します。

```sh
npm -g i pnpm
pnpm i
```

### コンテナ管理ツールのインストールと設定

下記のページを参考にPodmanとPodman Composeをインストールしてください。

- [Podman Installation | Podman](https://podman.io/docs/installation)
- [Podman Compose Installation](https://github.com/containers/podman-compose#installation)

既にDockerとDocker Composeがインストールされている場合は、それを使うこともできます。「情報ネットワーク応用」の科目を以前に履修している場合はインストールされている可能性が高いです。（演習の時間に入れさせられたため）

`.env.sample`ファイルを`.env`ファイルにコピーしてください。Dockerを使用している場合は、中の`podman`の部分を`docker`に書き換えてください。
