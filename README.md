# ystandard

![ystandard](./screenshot.png "ystandard")

## 普通とは一味違ったテーマ

サイト高速化、Google PageSpeed Insightsでの高得点獲得に重点を置きながら、比較的カスタマイズしやすい形を目指したテーマ

テンプレートと機能面をなるべく分離し、子テーマで拡張することを前提に作っています。

表示に関するいろいろな部分が関数化されていてやや癖のあるテーマですが、慣れれば子テーマでガツガツカスタマイズしていけるはずです…


## 標準的にあると嬉しい機能を盛り込む

ブログ運営・サイト制作をしていて「この機能が標準的に使えたらいい」という機能を続々盛り込んでいます。
（≒いつも同じようなプラグインをインストールして設定したり、同じようなコードを何度も書くのがめんどくさい）

詳しくは「主な機能」を御覧ください。

（※設定した情報は他のテーマと共有できませんので、テーマ変更の際に設定の変更・移行が大変になるかと思います。運営スタイルに合わせて利用を検討ください）


## 「ystandard」の由来

「標準」の意の「standard」に作者が自作物やハンドルネームによく使う「ys」というプレフィックスをくっつけて、「ystandard」にしました。
（「ys-standard」という案もありました。）

先頭の「y」に意味はなく、発音する必要も無いと思っておりましたが、「ystandard」を「y」の部分まで発音すると「why standard」に聞こえることから「普通とは一味違ったテーマ」というコンセプトを掲げています


## 必要な動作環境

- WordPress : 4.5以上
- PHP : 5.6以上


## 主な機能

- Google Analyticsのタグ出力を管理画面から設定可
- OGPの出力（管理画面で設定を行う必要あり）
- カテゴリー、タグ、日別ページのnoindex設定
- AMPフォーマット出力（β版）
- 筆者のSNSリンク出力機能
- 筆者の画像変更機能
- 記事毎にnoindexの設定が可能
- PageSpeed Insightsの提案に沿ったCSSの配信最適化
- 簡易的なPVカウント機能
- 関連する人気記事出力ウィジェット
- 広告表示用ウィジェット

## 注意事項

- モバイル表示ではサイドバー部分が出力されません（設定ページにて変更可）
- AMP表示では各ウィジェットが出力されません
- 絵文字関連のcss、javascriptが出力されません（設定ページにて変更可）
- oembed関連のcss、javascriptが出力されません（設定ページにて変更可）

## メニューについて

### メニュー位置

1. グローバルメニュー
2. フッターメニュー

上記2種類のメニューを用意

### メニュー階層

- グローバルメニュー
  - 子項目まで対応（孫項目については設定しても表示されません）
- フッターメニュー
  - 階層メニュー非対応。階層メニューを設定しても、親メニューしか表示されません。

## テーマ独自のウィジェット

### 関連する人気記事出力ウィジェット

テーマに実装されている簡易PVカウント機能でカウントしたPV数を使用し人気記事のランキングを出力します。

個別記事・カテゴリーアーカイブページでは同一カテゴリーに属する記事のランキングを表示し、それ以外の場合はサイト全体の人気記事ランキングを表示します。

### 広告表示用ウィジェット

404ページと検索結果が0件の場合に設定した内容を出力しないウィジェットです。

「価値のないページ」に出力すると警告を受ける広告コード等の表示にお使いください。


## スタイルについて

### sass

ystandard側で作成したスタイルは`/sass/ystandard/`以下で定義しています。

`/sass/_ys_custom_variables.scss`で変数を上書き、`/sass/_ys_customize.scss`で作成するテーマ独自のスタイルを追加していく想定で作成しています。

## 設定項目

### 簡単設定

- SNSまとめて設定
  - Twitter
    - アカウント名
  - Facebook
    - app_id
    - admins

### 基本設定

- Copyright
  - 発行年
- アクセス解析設定
  - Google Analytics トラッキングID
- シェアボタン設定
  - Twitterシェアにviaを付加する（要Twitterアカウント名設定）
  - Twitterアカウント名
- OGP・Twitterカード設定
  - Facebook app_id
  - Facebook admins
  - Twitterアカウント名
  - OGPデフォルト画像
- サイト表示設定
  - モバイル表示でもサイドバー部分を出力する
  - 絵文字関連スタイルシート・スクリプトを出力する
  - oembed関連スタイルシート・スクリプトを出力する
- SNSフォローURL
  - Twitter
  - Facebook
  - Google+
  - Instagram
  - tumblr
  - Youtube
  - Github
  - Pinterest
  - LinkedIn
- 投稿設定
  - 同じカテゴリーの関連記事を出力する
  - 次の記事・前の記事を出力しない
- SEO対策設定
  - アーカイブページのnoindex設定
- 広告設定
  - 記事タイトル下
  - moreタグ部分
  - PC表示：記事本文下（左右）
  - スマホ表示：記事本文下

### 高度な設定

- 投稿設定
  - 個別ページでアイキャッチ画像を表示しない
- css,javascript設定
  - jQueryをCDNから読み込む（URLを設定）　（※追加予定）
- AMP有効化
  - AMPページを生成する（β）

### AMP設定（β）

- シェアボタン設定
  - Facebook app_id
- 通常ビューへのリンク表示
  - コンテンツ上部
  - シェアボタン下
- AMP記事作成条件設定
  - scriptタグの削除
  - styleタグの削除
- 広告設定
  - 記事タイトル下
  - moreタグ部分
  - 記事本文下


## 履歴

### v0.1.x
- v0.1.1 : 2017/02/12 個別投稿の構造化データでauthorがエラーになる問題対処
- v0.1.0 : 2017/02/12 ベータ版公開

### v0.0.x

- 2017/02/06：ソース管理をGitHubに移行
- 2016/12/xx：「Google PageSpeed Insightsでの高得点を出しやすい」「速い」をコンセプトに再作成開始
- 2016/11/15：とりあえずGithubにて公開…の予定をやっぱりやめてもう一度構想から練り直し
- 2016/10/xx：開発開始
