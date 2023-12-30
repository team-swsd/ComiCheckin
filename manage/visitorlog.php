<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/destyle.css@1.0.15/destyle.css" />
  <link rel="stylesheet" href="../common.css" />
  <link rel="stylesheet" href="./visitorlog.css" />

  <title>チェックイン画面 | SWSD コミケチェックインシステム</title>
</head>

<body>
  <header>
    <h1>Team SWSD ComiCheckin</h1>
  </header>
  <main>
    <div class="main-container">
      <?php
      // 来訪一覧
      // とりあえず確認のみ

      require_once(dirname(__FILE__) . '/../lib/db.php');

      $result = $db->query('SELECT value FROM visitlog ORDER BY id DESC');
      ?>
      <table>
        <thead>
          <tr>
            <td>Time</td>
            <td>Name</td>
            <td>Target</td>
          </tr>
        </thead>
        <tbody>
          <?php
          if ($result) {
            while ($row = $result->fetchArray()) {
              $arr = json_decode($row['value'], true);
              echo "<tr>\n";
              echo "<td>" . $arr["date"] . "</td>\n";
              echo "<td class=\"visitor-td\">" . $arr["visitorName"] . "</td>\n";
              echo "<td>";
              for ($i = 0; $i < count($arr["target"]); $i++) {
                echo $arr["target"][$i];
                if ($i < count($arr["target"]) - 1) {
                  echo ", ";
                }
              }

              echo "</td>\n";
            }
          }

          ?>
        </tbody>
      </table>
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
