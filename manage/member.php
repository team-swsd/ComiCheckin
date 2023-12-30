<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/destyle.css@1.0.15/destyle.css" />
  <link rel="stylesheet" href="../common.css" />
  <link rel="stylesheet" href="./member.css" />

  <title>チェックイン画面 | SWSD コミケチェックインシステム</title>
</head>

<body>
  <header>
    <h1>Team SWSD ComiCheckin</h1>
  </header>
  <main>
    <div class="main-container">
      <?php
      // メンバー一覧
      // 追加と削除ができる

      require_once(dirname(__FILE__) . '/../lib/db.php');

      // 新規追加があったら
      if (isset($_POST['addName'])) {
        try {
          $stmt = $db->prepare('INSERT INTO member VALUES(NULL, :name)');
          $stmt->bindValue(':name', $_POST['addName'], SQLITE3_TEXT);
          $stmt->execute();
        } catch (Exception $e) {
          error_log("[insert member error!] " . $e->getMessage(), 0);
          echo "INSERT MEMBER ERROR<br>";
          echo "<a href =\"/\">Return to top</a>";
          exit;
        }
      }

      if (isset($_POST['deleteId'])) {
        try {
          $stmt = $db->prepare('DELETE from member where id = :id');
          $stmt->bindValue(':id', $_POST['deleteId'], SQLITE3_TEXT);
          $stmt->execute();
        } catch (Exception $e) {
          error_log("[delete member error!] " . $e->getMessage(), 0);
          echo "DELETE MEMBER ERROR<br>";
          echo "<a href =\"/\">Return to top</a>";
          exit;
        }
      }

      //一覧出力

      // SELECTする
      $result = $db->query('SELECT * FROM member');

      ?>
      <table border="1">
        <thead>
          <tr>
            <td>ID</td>
            <td>Name</td>
            <td></td>
          </tr>
        </thead>
        <tbody>
          <?php
          if ($result) {
            while ($row = $result->fetchArray()) {
              echo "<tr>\n";
              echo "<td>" . $row["id"] . "</td>\n";
              echo "<td>" . $row["name"] . "</td>\n";
              echo "<td>"
                . "<form action=\"/manage/member.php\" method=\"POST\">\n"
                . "<input type=\"hidden\" name=\"deleteId\" value=\"" . $row["id"] . "\"/>\n"
                . "<input class=\"delete-btn\" type=\"submit\" value=\"削除\" />\n"
                . "</form>"
                . "</td>\n";
              echo "</tr>\n";
            }
          }

          ?>
        </tbody>
      </table>

      <div>
        <p>
        <form action="/manage/member.php" method="POST">
          <div>
            <input class="name-input" placeholder="名前を入力" type="text" name="addName" id="addName" required />
          </div>
          <div>
            <input class="submit-btn" type="submit" value="登録" />
          </div>
        </form>
        </p>
      </div>
    </div>
  </main>
  <footer>
    <button onclick="linkTo(`/manage/index.php`)">管理画面へ</button>
  </footer>
  <script>
    function linkTo(to) {
      window.document.location.href = to;
    }
  </script>
</body>

</html>
