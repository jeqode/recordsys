<?php 
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("AccessControl-Allow-Credentials: true");
header("Content-Type: application/json");
include_once '../config/database.php';
include_once '../record.php';

$database = new Database();
$db = $database->getConnection();
$record = new Record($db);
$record->record_time = isset($_POST['record_time']) ? $_POST['record_time'] : die();
$stmt = $record->delete();
$num = $stmt->rowCount();

if($num){
    print("{\"message\": \"ลบข้อมูล {$stmt->rowCount()} รายการสำเร็จ\"}");
}else{
    print("{\"message\": ไม่สามารถลบข้อมูลใด้!\"}");
}
?>