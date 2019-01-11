<?php 
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("AccessControl-Allow-Credentials: true");
header("Content-Type: application/json");
include_once '../config/database.php';
include_once '../record.php';

$database = new Database();
$db = $database->getConnection();
$record = new Record($db);
$record->record_time = isset($_POST['record_time']) ? $_POST['record_time'] : "die()";
$record->doc_number = isset($_POST['doc_number']) ? $_POST['doc_number'] : "die()";
$record->visit_date = isset($_POST['visit_date']) ? $_POST['visit_date'] : "die()";
$record->occupation = isset($_POST['occupation']) ? $_POST['occupation'] : "die()";
$record->n_people = isset($_POST['n_people']) ? $_POST['n_people'] : "die()";
$record->address = isset($_POST['address']) ? $_POST['address'] : "address die()";
$record->district = isset($_POST['district']) ? $_POST['district'] : "district die()";
$record->province = isset($_POST['province']) ? $_POST['province'] : "die()";
$record->meal_price = isset($_POST['meal_price']) ? $_POST['meal_price'] : "die()";
$record->meal_quantity = isset($_POST['meal_quantity']) ? $_POST['meal_quantity'] : "die()";
$record->personal_room = isset($_POST['personal_room']) ? $_POST['personal_room'] : "die()";
$record->personal_room_quantity = isset($_POST['personal_room_quantity']) ? $_POST['personal_room_quantity'] : "die()";
$record->group_room = isset($_POST['group_room']) ? $_POST['group_room'] : "die()";
$record->group_room_quantity = isset($_POST['group_room_quantity']) ? $_POST['group_room_quantity'] : "die()";
$record->meeting_room = isset($_POST['meeting_room']) ? $_POST['meeting_room'] : "die()";
$stmt = $record->editOne();
$num = $stmt->rowCount();

if($num){
    print("{\"message\": \"แก้ไขข้อมูล {$num} รายการสำเร็จ\"}");
}else{
    print("{\"message\": ไม่มีการแก้ไขข้อมูล\"}");
}


?>