絶賛開発中

# ComiCheckin

コミケでサークルメンバーのだれかに挨拶しようとしてくれた方のチェックインシステム

## 構成説明

### HTML出力
- `/index.php`
  - 訪問者に名前を記載してもらう画面
  - 登録自体はAjaxで`/api/checkin.php`を叩く
- `/manage/index.php`
  - 裏方画面トップページ
    - ここから"売れた" "緊急"ボタン等のAPIを叩ける
      - `/api/purchase.php`
- `/manage/member.php`
  - サークルメンバー一覧管理画面。追加/削除が行える。FormDataのPOSTで処理
- `/manage/visitorlog.php`
  - 訪問ログ表示
  - GETのみ

### API
- `/api/checkin.php`
  - 以下のようなJSON形式をPOSTして受付
    ```json
    {
      "visitor": "hoge",
      "target": [
        "ke9000",
        "mikuta0407"
      ]
    }
    ```
    応答は
    ```json
    {"result":"Created"}
    ```
    が201と共に返却。
    返却後、`/lib/discordWebhook.php`内の`discordWebhook()`を呼び出してDiscordWebhookを送信する。内容は
    ```
    <訪問者名>さんが<メンバー>を訪れました! 時刻: 1970-01-01 00:00:00
    ```
    が飛ぶ。
- `/api/purchase.php`
  - 以下のようなJSON形式をPOSTして受付
    ```json
    {"item_name": "Hoge Book Name"}
    ```
    応答は
    ```json
    {"result":"Created","ID":15}
    ```
    が201と共に返却。IDはテーブル内ID。
    返却後、`/lib/discordWebhook.php`内の`discordWebhook()`を呼び出してDiscordWebhookを送信する。内容は
    ```
    1970-01-01 00:00:00 <アイテム名> が売れました! 総数: <アイテム名のレコードの総数>
    ```
    が飛ぶ

### 内部ライブラリ
- `/lib/db.php`
  - DBファイルを掴む。なければ作る。
- `/lib/discordWebhook.php`
  - `/.env`内`DISCORDENDPOINT`変数に指定されているDiscordのWebhookのエンドポイントを取得
  - `discordWebhook(string)`
    - 引数に入ってきた文字列をリクエストボディの`content`内に入れてDiscordWebhookを叩く
    
## 使い方

- 来客者向け
  1. /index.phpを表示した端末を見せる
  2. テキストボックスに名前を入れてもらう
  3. チェックボックスで挨拶対象を選んでもらう
  4. チェックインボタンを押してもらう
  5. (サークルメンバーが)トップ画面へ戻す
- 管理側向け
  1. /manage/member.phpでサークルメンバーのメンバーを設定する
  2. チェックインしてもらう
  3. /manage/visitorlog.phpでログを見る
