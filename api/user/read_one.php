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
$user->username = isset($_GET['username']) ? $_GET['username'] : die();
$user->readOne();
$user_array = array(
	"id" => $user->id,
	"username" => $user->username,
	"is_admin" => $user->is_admin
);
if(isset($user->id)){
	print_r(json_encode($user_array));
}else{
	print("{\"message\": \"Username is not found.\"}");
}
?>