<?php 
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../config/database.php';
include_once '../record.php';

$database = new Database();
$db = $database->getConnection();

$record = new Record($db);

$stmt = $record->readAll();
$num = $stmt->rowCount();

if($num>0){
	$record_array = array();
	$record_array["success"] = true;
	$record_array["data"] = array();

	while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
		$record_item = array(
			"record_time" =>  $record_time,
            "doc_number" =>  html_entity_decode($doc_number),
            "visit_date" =>  $visit_date,
            "occupation" =>  html_entity_decode($occupation),
            "n_people" =>  $n_people,
            "address" =>  html_entity_decode($address),
            "district" =>  html_entity_decode($district),
            "province" =>  html_entity_decode($province),
            "meal_price" =>  $meal_price,
            "meal_quantity" =>  $meal_quantity,
            "personal_room" =>  html_entity_decode($personal_room),
            "personal_room_quantity" =>  $personal_room_quantity,
            "group_room" =>  html_entity_decode($group_room),
            "group_room_quantity" =>  $group_room_quantity,
            "meeting_room" =>  html_entity_decode($meeting_room)
		);
		array_push($record_array["data"], $record_item);
	}
	echo json_encode($record_array);
} else{
	echo json_encode(
		array("success" => false, "message" => "No record found.")
	);
}
?>
