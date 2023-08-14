絶賛開発中

# ComiCheckin

コミケでサークルメンバーのだれかに挨拶しようとしてくれた方のチェックインシステム

## ファイル説明

- /index.php
  - 名前書いて、挨拶対象にチェック入れて、チェックインする
- /checkin.php
  - index.phpから飛ぶ先(ありがとうございます、見たいのを表示したい)
- /lib/db.php
  - DBなかったら作り、あったら掴む
- /manage/index.php
  - 管理用メニュー一覧
- /manage/member.php
  - サークルメンバーの追加・削除
- /manage/visitorlog.php
  - 来てくれたログ(閲覧のみ。フィルターなし)

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