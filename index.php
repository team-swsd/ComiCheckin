<?php
// チェックイン画面
// memberから一覧取ってきてCheckBox一覧を作る
?>

<form action="checkin.php" method="POST">
    <div>
        <label for="name">Enter your name: </label>
        <input type="text" name="visitorName" id="visitorName" required />
    </div>
    <div>
        <label for="memberCB">

            <?php
            require_once(dirname(__FILE__) . '/lib/db.php');
            $result = $db->query('SELECT * FROM member');
            $i = 0;
            if ($result) {
                while ($row = $result->fetchArray()) {
                    echo "<label for=\"memberCB$i\">\n"; // member Check Box
                    echo "<input type=\"checkbox\" name=\"memberCB[]\" value=\"" . $row["name"] . "\"/>" . $row["name"] . "\n";
                    echo "</label>\n";
                }
            }

            ?>
        </label>
    </div>
    <div>
        <input type="submit" value="チェックイン" />
    </div>

    <a href="/manage/">Manage page</a>