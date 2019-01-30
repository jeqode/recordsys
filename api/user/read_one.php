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
$user->username = isset($_GET['username']) ? $_GET['username'] : die();
$user->readOne();

if(isset($user->id)){
	$user_array = array(
		"id" => $user->id,
		"username" => $user->username,
		"is_admin" => $user->is_admin
	);
	$user->res['success'] = true;
	$user->res['data'] = $user_array;
}else{
	$user->res['success'] = false;
	$user->res['message'] = "ไม่พบชื่อผู้ใช้งาน";
}
echo json_encode($user->res);
?>