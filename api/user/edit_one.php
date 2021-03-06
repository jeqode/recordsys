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
$user->username = isset($_POST['username']) ? $_POST['username'] : die();
$user->auth_hash = isset($_POST['password']) ? $_POST['password'] : "";
$user->is_admin = isset($_POST['is_admin']) ? $_POST['is_admin'] : die();
$stmt = $user->editOne();
$num = $stmt->rowCount();
if ($num){
    $user->res['success'] = true;
    $user->res['message'] = "{$num} รายการแก้ไขสำเร็จ";
}else{
    $user->res['message'] = "แก้ไขข้อมูลไม่สำเร็จ";
    $user->res['success'] = false;
}
echo json_encode($user->res);

?>