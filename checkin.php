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

require_once(dirname(__FILE__) . '/lib/db.php');

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
$json = json_encode($discordContent);

$url = "https://discord.com/api/webhooks/1190247646972096586/NbX5wZMSYW9IIkRRFYR7lNDFBtoys0kfyqKRsU3-sMESeV3gWoFJ-FVcJrhUvfUBM9jj";
$ch = curl_init();
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_URL, $url);
$result=curl_exec($ch);
curl_close($ch);
?>
