<?php 
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("AccessControl-Allow-Credentials: true");
header("Content-Type: application/json");
session_start();

include_once '../config/database.php';
include_once '../user.php';

$database = new Database();
$db = $database->getConnection();
$user = new User($db);

$user->username = (isset($_POST['username']) && $_POST['username'] != "") ? $_POST['username']:die();
$password = (isset($_POST['password']) && $_POST['password'] != "") ? $_POST['password']:die();

$user->readOne();
if (isset($user->username) && $user->username != ""){
	if(password_verify($password, $user->auth_hash)){
		$_SESSION['id'] = $user->id;
		$_SESSION['user'] = $user->username;
		$_SESSION['username'] = $user->username;
		$_SESSION['auth_hash'] = $user->auth_hash;
		$_SESSION['is_admin'] = $user->is_admin;
	}else{
		$_SESSION['msg'] = "ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง !";
	}
}else{
	$_SESSION['msg'] = "ไม่มีชื่อผู้ใช้ !";
}
header("location: ../../");
?>