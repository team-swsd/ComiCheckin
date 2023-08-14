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
                    . "<input type=\"submit\" value=\"削除\" />\n"
                    . "</form>"
                    . "</td>\n";
                echo "</tr>\n";
            }
        }

        ?>
    </tbody>
</table>

<?php

?>

<div>
    <p>
    <form action="/manage/member.php" method="POST">
        <div>
            <label for="name">Enter new member name: </label>
            <input type="text" name="addName" id="addName" required />
        </div>
        <div>
            <input type="submit" value="登録" />
        </div>
    </form>
    </p>
</div>
<div>
    <p>
        <input type="button" value="Reload" onclick="var loc = window.location; window.location = loc.protocol + '//' + loc.host + loc.pathname + loc.search;" />
    </p>
</div>

<a href="/manage/visitorlog.php">Visitor log</a><br>
<a href ="/">Return to top</a>