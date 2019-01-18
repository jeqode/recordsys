<?php 
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../config/database.php';
include_once '../record.php';

$database = new Database();
$db = $database->getConnection();

$record = new Record($db);
$record->record_time = isset($_POST['record_time']) ? $_POST['record_time'] : die();
$record->readOne();

$record_array = array(
    "doc_number" => $record->doc_number,
    "visit_date" => $record->visit_date,
    "occupation" => $record->occupation,
    "address" => $record->address,
    "district" => $record->district,
    "province" => $record->province,
    "country" => $record->country,
    "n_people" => $record->n_people,
    "meal_price" => $record->meal_price,
    "meal_quantity" => $record->meal_quantity,
    "personal_room" => $record->personal_room,
    "personal_room_quantity" => $record->personal_room_quantity,
    "group_room" => $record->group_room,
    "group_room_quantity" => $record->group_room_quantity,
    "meeting_room" => $record->meeting_room
);
if(isset($record->visit_date)){
	print_r(json_encode($record_array));
}else{
	print("{\"message\": \"ไม่พบรายการ\"}");
}
?>
