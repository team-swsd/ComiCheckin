<?php

// 実際のチェックイン処理

if (!(isset($_POST['memberCB']))){
    echo "no check\n";
    exit;
}

require_once(dirname(__FILE__) . '/lib/db.php');

$jsonarr["date"] = date("Y-m-d H:i:s");
$jsonarr["visitorName"] = $_POST['visitorName'];
$jsonarr["target"] = $_POST['memberCB'];

try {
    $stmt = $db->prepare('INSERT INTO visitlog VALUES(NULL, :json)');
    $stmt->bindValue(':json', json_encode($jsonarr), SQLITE3_TEXT);
    $stmt->execute();
} catch (Exception $e) {
    error_log("[insert visitlog error!] ". $e->getMessage(), 0);
    echo "INSERT ERROR<br>";
    echo "<a href =\"/\">Return to top</a>";
    exit;
}
?>

check in!<br>
<a href ="/">Return to top</a>
