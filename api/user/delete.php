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
$user->id = isset($_POST['id']) ? $_POST['id'] : die();
$stmt = $user->delete();
$num = $stmt->rowCount();

if($num){
    $user->res['success'] = true;
    $user->res['message'] = "ลบ {$stmt->rowCount()} ผู้ใช้สำเร็จ";
}else{
    $user->res['message'] = "ไม่สามารถลบผู้ใช้ใด้!";
    $user->res['success'] = false;
}
echo json_encode($user->res);
?>