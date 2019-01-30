<?php 
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../config/database.php';
include_once '../user.php';

$db = new Database();
$user = new User($db);

$stmt = $user->readAll();
$num = $stmt->rowCount();

if($num>0){
	$user->res = array();
	$user->res['success'] = true;
	$user->res["data"] = array();

	while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
		extract($row);
		$user_item = array(
			"id" => $id,
			"username" => html_entity_decode($username),
			"is_admin" => $is_admin
		);
		array_push($user->res["data"], $user_item);
	}
	
} else{
	$user->res['success'] = false;
	$user->res['message'] = "ไม่พบข้อมูล";
}
echo json_encode($user->res);
?>
