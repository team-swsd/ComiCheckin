<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/destyle.css@1.0.15/destyle.css" />
  <link rel="stylesheet" href="./common.css" />
  <link rel="stylesheet" href="./index.css" />
  <link rel="manifest" href="./manifest.json">
    <script>
      if ('serviceWorker' in navigator) {
        navigator.serviceWorker.register('./sw.js').then(registration => {
          console.log('ServiceWorker registration successful.');
        }).catch(err => {
          console.log('ServiceWorker registration failed.');
        });
      }
    </script>

  <title>チェックイン画面 | SWSD コミケチェックインシステム</title>
</head>

<body>
  <header>
    <h1>Team SWSD ComiCheckin</h1>
  </header>
  <main>
    <div class="main-container">
      <?php
      // チェックイン画面
      // memberから一覧取ってきてCheckBox一覧を作る
      ?>
      <form name="checkinForm" action="#">
        <div>
          <input class="name-input" placeholder="名前" type="text" name="visitorName" id="visitorName" required />
        </div>
        <div>
          <?php
          require_once(dirname(__FILE__) . '/lib/db.php');
          $result = $db->query('SELECT * FROM member');
          $i = 0;
          if ($result) {
            while ($row = $result->fetchArray()) {
              echo "<div class=\"cb-area\">" . "\n";
              echo "<input type=\"checkbox\" name=\"memberCB\" id=\"memberCB$i\" value=\"" . $row["name"] . "\"/>" . "\n";
              echo "<label for=\"memberCB$i\">" . $row["name"] . "</label>\n";
              echo "</div>" . "\n";
              $i++;
            }
          }
          ?>
        </div>
      </form>
      <div>
        <button id="checkin-btn" onClick="doSubmit()" class="checkin-btn">チェックイン！</button>
      </div>
    </div>
  </main>
  <footer>
    <button onclick="linkTo(`/manage/index.php`)">管理画面へ</button>
  </footer>
  <script>
    function linkTo(to) {
      window.location.href = to;
    }

    async function doSubmit() {
      const form = document.forms.checkinForm;
      const visitor = form.visitorName.value;
      const cb = form.memberCB;
      const target = []
      const checkinBtn = document.getElementById("checkin-btn")
      checkinBtn.style.backgroundColor = "#B5B5B5";

      Array.prototype.forEach.call(cb, (item) => {
        if (item.checked) {
          target.push(item.value)
        }
      })

      const reqBody = JSON.stringify({
        visitor,
        target
      })

      try {
        const res = await fetch("/checkin.php", {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: reqBody
        })
        if (res.status === 201) {
          alert("登録しました。")
        } else {
          const resJson = await res.json()
          alert(`エラーが発生しました。: ${resJson.result}`)
        }
      } catch (e) {
        alert(`エラーが発生しました: ${e}`)
      }
      checkinBtn.style.backgroundColor = "#03963D";
    }
  </script>
</body>

</html>
