<?php
// 来訪一覧
// とりあえず確認のみ

require_once(dirname(__FILE__) . '/../lib/db.php');

$result = $db->query('SELECT value FROM visitlog');
?>
<table border="1">
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
                echo "<td>" . $arr["visitorName"] . "</td>\n";
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

<a href="/manage/member.php">Member</a><br>
<a href ="/">Return to top</a>