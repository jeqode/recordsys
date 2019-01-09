<?php 
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("AccessControl-Allow-Credentials: true");
header("Content-Type: application/json");
include_once '../config/database.php';
include_once '../user.php';

$database = new Database();
$db = $database->getConnection();
$user = new User($db);
$user->username = isset($_POST['id']) ? $_POST['id'] : die();
$stmt = $user->delete();
$num = $stmt->rowCount();

if($num){
    print("{\"message\": \"ลบ {$stmt->rowCount()} ผู้ใช้สำเร็จ\"}");
}else{
    print("{\"message\": ไม่สามารถลบผู้ใช้ใด้!\"}");
}
?>