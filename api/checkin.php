<?php
// 実際のチェックイン処理
$json = file_get_contents('php://input');
$data = json_decode($json, true);

if (!isset($data["visitor"]) || $data["visitor"] == ""){
    $res["result"] = "No Input Name";
    header("HTTP/1.1 400 Bad Request");
    echo json_encode($res);
    exit;
}

if (!isset($data["target"]) || count($data["target"]) == 0){
    $res["result"] = "No Check";
    header("HTTP/1.1 400 Bad Request");
    echo json_encode($res);
    exit;
}

$time = new DateTime();
$time->setTimeZone(new DateTimeZone('Asia/Tokyo'));
$insertJson["date"] = $time->format("Y-m-d H:i:s");
$insertJson["visitorName"] = $data["visitor"];
$insertJson["target"] = $data["target"];

require_once(dirname(__FILE__) . '/../lib/db.php');

try {
    $stmt = $db->prepare('INSERT INTO visitlog VALUES(NULL, :json)');
    $stmt->bindValue(':json', json_encode($insertJson), SQLITE3_TEXT);
    $stmt->execute();
} catch (Exception $e) {
    error_log("[insert visitlog error!] ". $e->getMessage(), 0);
    $res["result"] = "INSERT ERROR";
    header("HTTP/1.1 400 Bad Request");
    echo json_encode($res);
    exit;
}

$res["result"] = "Created";
header("HTTP/1.1 201 Created");
echo json_encode($res);

// DiscordWebhook組み立て

$targets = "";
for ($i = 0; $i < count($data["target"]); $i++){
    $targets = $targets . $data["target"][$i];
    if ($i != count($data["target"]) - 1) {
        $targets = $targets . ", ";
    }
    
}
$discordContent["content"] = $data["visitor"] . "さんが " . $targets ." を訪れました! 時刻: ". $insertJson["date"];

require_once(dirname(__FILE__) . '/../lib/discordWebhook.php');
discordWebhook($discordContent);

?>
