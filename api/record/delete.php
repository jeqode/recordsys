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

$record->record_time = isset($_POST['record_time']) ? $_POST['record_time'] : NULL;
$record->res['success'] = false;
$record->res['message'] = "ไม่สามารถลบข้อมูลใด้!";
if ($record->record_time){
	$stmt = $record->delete();
	$num = $stmt->rowCount();
	if($num){
		$record->res['success'] = true;
		$record->res['message'] = "ลบข้อมูล {$stmt->rowCount()} รายการสำเร็จ";
	}
}else{
	$record->res['error'] = "กรุณาระบุรายการที่ต้องการลบ";
}
echo json_encode($record->res);
?>