<?php 
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../config/database.php';
include_once '../record.php';

$db = new Database();
$record = new Record($db);
$month = isset($_POST['month']) ? $_POST['month'] : "%";
$year = isset($_POST['year']) ? $_POST['year'] : "%";
$occupation = isset($_POST['occupation']) ? $_POST['occupation'] : "%";
$province = isset($_POST['province']) ? $_POST['province'] : "%";
$country = isset($_POST['country']) ? $_POST['country'] : "%";

$stmt = $record->search($month, $year, $occupation, $province, $country);
$num = $stmt->rowCount();

if($num>0){
	$record->res["data"] = array();
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
		array_push($record->res["data"], $record_item);
		$record->res['message'] = "พบข้อมูลทั้งหมด {$num} รายการ";
	}
} else{
	$record->res["success"] = false;
	$record->res["message"] = "ไม่พบข้อมูล";
}
echo json_encode($record->res);
?>
