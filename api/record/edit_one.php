<?php 
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("AccessControl-Allow-Credentials: true");
header("Content-Type: application/json");
include_once '../config/database.php';
include_once '../record.php';

$db = new Database();
$record = new Record($db);
$record->record_time =  isset($_POST['record_time']) && $_POST['record_time'] ? "'".$_POST['record_time']."'" : "NULL";
$record->doc_number =  isset($_POST['doc_number']) && $_POST['doc_number'] ? "'".$_POST['doc_number']."'" : "NULL";
$record->visit_date =  isset($_POST['visit_date']) && $_POST['visit_date'] ? "'".$_POST['visit_date']."'" : "NULL";
$record->occupation =  isset($_POST['occupation']) && $_POST['occupation'] ? "'".$_POST['occupation']."'" : "NULL";
$record->n_people =  isset($_POST['n_people']) && $_POST['n_people'] ? "'".$_POST['n_people']."'" : "NULL";
$record->address =  isset($_POST['address']) && $_POST['address'] ? "'".$_POST['address']."'" : "NULL";
$record->district =  isset($_POST['district']) && $_POST['district'] ? "'".$_POST['district']."'" : "NULL";
$record->province =  isset($_POST['province']) && $_POST['province'] ? "'".$_POST['province']."'" : "NULL";
$record->country =  isset($_POST['country']) && $_POST['country'] ? "'".$_POST['country']."'" : "NULL";
$record->meal_breakfast_price =  isset($_POST['meal_breakfast_price']) && $_POST['meal_breakfast_price'] ? "'".$_POST['meal_breakfast_price']."'" : "NULL";
$record->meal_lunch_price =  isset($_POST['meal_lunch_price']) && $_POST['meal_lunch_price'] ? "'".$_POST['meal_lunch_price']."'" : "NULL";
$record->meal_dinner_price =  isset($_POST['meal_dinner_price']) && $_POST['meal_dinner_price'] ? "'".$_POST['meal_dinner_price']."'" : "NULL";
$record->refreshment_morning_price =  isset($_POST['refreshment_morning_price']) && $_POST['refreshment_morning_price'] ? "'".$_POST['refreshment_morning_price']."'" : "NULL";
$record->refreshment_afternoon_price =  isset($_POST['refreshment_afternoon_price']) && $_POST['refreshment_afternoon_price'] ? "'".$_POST['refreshment_afternoon_price']."'" : "NULL";
$record->refreshment_evening_price =  isset($_POST['refreshment_evening_price']) && $_POST['refreshment_evening_price'] ? "'".$_POST['refreshment_evening_price']."'" : "NULL";
$record->meeting_room_name =  isset($_POST['meeting_room_name']) && $_POST['meeting_room_name'] ? "'".$_POST['meeting_room_name']."'" : "NULL";
$record->meeting_room_price =  isset($_POST['meeting_room_price']) && $_POST['meeting_room_price'] ? "'".$_POST['meeting_room_price']."'" : "NULL";
$record->single_decker_tram =  isset($_POST['single_decker_tram']) && $_POST['single_decker_tram'] ? "'".$_POST['single_decker_tram']."'" : "NULL";
$record->double_decker_tram =  isset($_POST['double_decker_tram']) && $_POST['double_decker_tram'] ? "'".$_POST['double_decker_tram']."'" : "NULL";
$record->rooms =  isset($_POST['rooms']) && $_POST['rooms'] ? "'".$_POST['rooms']."'" : "NULL";
$record->activities =  isset($_POST['activities']) && $_POST['activities'] ? "'".$_POST['activities']."'" : "NULL";
$record->contact =  isset($_POST['contact']) && $_POST['contact'] ? "'".$_POST['contact']."'" : "NULL";
$stmt = $record->editOne();
$num = $stmt->rowCount();
if($num){
	$record->res['success'] = true;
	$record->res['message'] = "แก้ไขข้อมูล {$num} รายการสำเร็จ";
}else{
	$record->res['success'] = false;
	$record->res['message'] = "ไม่มีการแก้ไขข้อมูล";
}
echo json_encode($record->res);
?>