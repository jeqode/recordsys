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
$user->username = isset($_POST['username']) ? $_POST['username'] : die();
$user->auth_hash = isset($_POST['password']) ? $_POST['password'] : "";
$user->is_admin = isset($_POST['is_admin']) ? $_POST['is_admin'] : die();
$stmt = $user->new();
$num = $stmt->rowCount();

if($num){
    print("{\"message\": \"เพิ่ม {$num} ผู้ใช้สำเร็จ\"}");
}else{
    print("{\"message\": ไม่สามารถเพิ่มผู้ใช้ได้ !\"}");
}


?>