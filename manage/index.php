<?php
require '../vendor/autoload.php';
Dotenv\Dotenv::createImmutable(__DIR__. "/../")->load();

// .envファイルで定義したGREETINGを変数に代入
$webhookWorker = $_ENV['WEBHOOKWORKER'];
?>

<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/destyle.css@1.0.15/destyle.css"
    />
    <link rel="stylesheet" href="./index.css" />

    <title>管理画面TOP | SWSD コミケチェックインシステム</title>
  </head>

  <body>
    <header>
      <h1>Team SWSD ComiCheckin</h1>
    </header>
    <main>
      <div class="main-container">
        <div class="btn-area-container">
          <div class="btn-area">
            <button onClick="linkTo(`/manage/member.php`)" class="color-1">
              <p>メンバー登録</p>
            </button>
          </div>
          <div class="btn-area">
            <button onClick="linkTo(`/manage/visitorlog.php`)" class="color-1">
              <p>訪問ログ</p>
            </button>
          </div>
        </div>
        <div class="btn-area-container">
          <div class="btn-area">
            <button
              onClick="notifyToDiscord(`SOLD`)"
              class="color-2"
              id="sold-btn"
            >
              <p>売れた</p>
            </button>
          </div>
          <div class="btn-area">
            <button
              onClick="notifyToDiscord(`EMERGENCY`)"
              class="color-3"
              id="emerg-btn"
            >
              <p>緊急</p>
            </button>
          </div>
        </div>
        <p style="margin-top: 16px">現在時刻: <span id="tokei"></span></p>
      </div>
    </main>
    <footer>
      <button>TOPへ戻る</button>
    </footer>

    <script>
      const URL = "<?php echo $webhookWorker;?>";

      function linkTo(to) {
        window.document.location.href = to;
      }

      function getCurrentDateTime() {
        const now = new Date();

        // 年、月、日、時、分、秒を取得
        const year = now.getFullYear();
        const month = String(now.getMonth() + 1).padStart(2, "0");
        const day = String(now.getDate()).padStart(2, "0");
        const hours = String(now.getHours()).padStart(2, "0");
        const minutes = String(now.getMinutes()).padStart(2, "0");
        const seconds = String(now.getSeconds()).padStart(2, "0");

        // フォーマットされた文字列を返す
        const formattedDateTime = `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;

        return formattedDateTime;
      }

      function updateTime() {
        const tokei = document.getElementById("tokei");
        setInterval(() => {
          const time = getCurrentDateTime();
          tokei.innerHTML = time;
        }, 1000);
      }

      async function notifyToDiscord(messageType) {
        const soldBtn = document.getElementById("sold-btn");
        const emergBtn = document.getElementById("emerg-btn");

        let content = "";

        if (messageType === "SOLD") {
          soldBtn.style.backgroundColor = "#B5B5B5";
          content = `売れました！時刻: ${getCurrentDateTime()}`;
        } else if (messageType === "EMERGENCY") {
          emergBtn.style.backgroundColor = "B5B5B5";
          content = `@everyone 緊急事態発生！時刻: ${getCurrentDateTime()}`;
        }
        const body = {
          content,
        };
        await fetch(URL, {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify(body),
        });

        alert("Discordへメッセージを送信しました");

        if (messageType === "SOLD") {
          soldBtn.style.backgroundColor = "#03963D";
        } else if (messageType === "EMERGENCY") {
          emergBtn.style.backgroundColor = "#DC047F";
        }
      }

      updateTime();
    </script>
  </body>
</html>
