<?php 
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../config/database.php';
include_once '../user.php';

$database = new Database();
$db = $database->getConnection();

$user = new User($db);

$stmt = $user->readAll();
$num = $stmt->rowCount();

if($num>0){
	$user_array = array();
	$user_array["success"] = true;
	$user_array["data"] = array();

	while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
		extract($row);
		$user_item = array(
			"id" => $id,
			"username" => html_entity_decode($username),
			"is_admin" => $is_admin
		);
		array_push($user_array["data"], $user_item);
	}
	echo json_encode($user_array);
} else{
	echo json_encode(
		array("success" => false, "message" => "No users found.")
	);
}
?>
