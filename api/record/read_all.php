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
            "country" =>  html_entity_decode($country),
            "meal_breakfast_price" => $meal_breakfast_price,
            "meal_lunch_price" => $meal_lunch_price,
            "meal_dinner_price" => $meal_dinner_price,
            "refreshment_morning_price" => $refreshment_morning_price,
            "refreshment_afternoon_price" => $refreshment_afternoon_price,
            "refreshment_evening_price" => $refreshment_evening_price,
            "meeting_room_name" => $meeting_room_name,
            "meeting_room_price" => $meeting_room_price,
            "single_decker_tram" => $single_decker_tram,
            "double_decker_tram" => $double_decker_tram,
            "rooms" => json_decode($rooms),
            "activities" => json_decode($activities),
            "contact" => $contact
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
