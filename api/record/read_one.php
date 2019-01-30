<?php 
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../config/database.php';
include_once '../record.php';

$db = new Database();
$record = new Record($db);
$record->record_time = isset($_GET['record_time']) ? $_GET['record_time'] : NULL;

if ($record->record_time){
	$record->readOne();
	if(isset($record->visit_date)){
		$record_array = array(
			"record_time" => $record->record_time,
			"doc_number" => $record->doc_number,
			"visit_date" => $record->visit_date,
			"occupation" => $record->occupation,
			"n_people" => $record->n_people,
			"address" => $record->address,
			"district" => $record->district,
			"province" => $record->province,
			"country" => $record->country,
			"meal_breakfast_price" => $record->meal_breakfast_price,
			"meal_lunch_price" => $record->meal_lunch_price,
			"meal_dinner_price" => $record->meal_dinner_price,
			"refreshment_morning_price" => $record->refreshment_morning_price,
			"refreshment_afternoon_price" => $record->refreshment_afternoon_price,
			"refreshment_evening_price" => $record->refreshment_evening_price,
			"meeting_room_name" => $record->meeting_room_name,
			"meeting_room_price" => $record->meeting_room_price,
			"single_decker_tram" => $record->single_decker_tram,
			"double_decker_tram" => $record->double_decker_tram,
			"rooms" => $record->rooms,
			"activities" => $record->activities,
			"contact" => $record->contact
		);
		$record->res['data'] = $record_array;
	}else{
		$record->res['success'] = false;
		$record->res['message'] = "ไม่พบรายการ";
	}
}else{
	$record->res['success'] = false;
	$record->res['error'] = "กรุณาระบุรายการที่ต้องการค้นหา";
	$record->res['message'] = "ไม่สามารถค้นข้อมูลได้";
}
echo json_encode($record->res);
?>
