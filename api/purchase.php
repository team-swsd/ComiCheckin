<?php
/*
curl -d '{"item_name": "Hoge Book Name"}'
*/

$json = file_get_contents('php://input');
$data = json_decode($json, true);

if (!isset($data["item_name"]) || $data["item_name"] == ""){
    $res["result"] = "No Input Name";
    header("HTTP/1.1 400 Bad Request");
    echo json_encode($res);
    exit;
}

require_once(dirname(__FILE__) . '/../lib/db.php');

$db->exec('BEGIN');
try {
    $item_name_safe_tmp = SQLite3::escapeString($data["item_name"]);
    $item_name_safe = htmlspecialchars($item_name_safe_tmp);
    $stmt = $db->prepare('INSERT INTO purchaselog (item_name) VALUES(:item_name)');
    $stmt->bindValue(':item_name', $item_name_safe, SQLITE3_TEXT);
    $stmt->execute();
    $lastID = $db->lastInsertRowID();
    $db->exec('COMMIT');
} catch (Exception $e) {
    $db->exec('ROLLBACK');
    error_log("[insert purchaselog error!] ". $e->getMessage(), 0);
    $res["result"] = "INSERT ERROR";
    header("HTTP/1.1 500 Internal Server Error");
    echo json_encode($res);
    exit;
}

$res["result"] = "Created";
$res["ID"] = $lastID;
header("HTTP/1.1 201 Created");
echo json_encode($res);

/* DiscordWebhook組み立て */

// 総数カウント
$count = $db->querySingle("SELECT COUNT(*) as count FROM purchaselog WHERE item_name = '$item_name_safe'");

// 情報取得
$sql = "SELECT item_name, created_at FROM purchaselog WHERE id=$lastID";
$arr = $db->querySingle($sql, true);
$purchaseData["item_name"] = $arr["item_name"];
$purchaseData["date"] = $arr["created_at"];

// 文字列組み立て
$discordContent["content"] = $purchaseData["date"] . " " . $purchaseData["item_name"] . " が売れました! 総数: $count";
$discordJson = json_encode($discordContent);

require_once(dirname(__FILE__) . '/../lib/discordWebhook.php');
discordWebhook($discordContent);
?>
