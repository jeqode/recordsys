<?php 
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("AccessControl-Allow-Credentials: true");
header("Content-Type: application/json");
include_once '../config/database.php';
include_once '../user.php';

$db = new Database();
$user = new User($db);
$user->username = isset($_POST['username']) ? $_POST['username'] : die();
$user->auth_hash = isset($_POST['password']) ? $_POST['password'] : "";
$user->is_admin = isset($_POST['is_admin']) ? $_POST['is_admin'] : die();

$stmt = $user->new();
$num = $stmt->rowCount();

if($num){
    $user->res['success'] = true;
    $user->res['message'] = "เพิ่ม {$num} ผู้ใช้สำเร็จ";
}else{
    $user->res['message'] = "ไม่สามารถเพิ่มผู้ใช้ได้ !";
    $user->res['success'] = false;
}
echo json_encode($user->res);
?>