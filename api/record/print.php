<?php
include_once '../config/database.php';
include_once '../record.php';

$database = new Database();
$db = $database->getConnection();

$record = new Record($db);
$month = isset($_GET['month']) ? $_GET['month'] : "%";
$year = isset($_GET['year']) ? $_GET['year'] : "%";
$occupation = isset($_GET['occupation']) ? $_GET['occupation'] : "%";
$province = isset($_GET['province']) ? $_GET['province'] : "%";
$country = isset($_GET['country']) ? $_GET['country'] : "%";
$stmt = $record->search($month, $year, $occupation, $province, $country);
$num = $stmt->rowCount();
?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>
	<div style="text-align:center;">
		<h1>รายงานข้อมูลคณะศึกษาดูงาน</h1>
		<h3>ศูนย์ศึกษาการพัฒนาเขาหินซ้อนอันเนื่องมาจากพระราชดำริ</h3>
	</div>
	<div id="SiXhEaD_Excel" align=center x:publishsource="Excel">
			<table x:str border="1" style="border-collapse:collapse;">
		<thead>
				<tr>
					<th rowspan="2">หมายเลข</th>
					<th rowspan="2" class="default-sort">วันที่</th>
					<th rowspan="2">กลุ่มอาชีพ</th>
					<th rowspan="2">จำนวน (คน)</th>
					<th rowspan="2">หน่วยงาน / ที่อยู่</th>
					<th colspan="3">ราคาอาหารมื้อหลัก</th>
					<th colspan="3">ราคาอาหารว่าง</th>
					<th colspan="2">รถรางดูงาน</th>
					<th rowspan="2">ห้องประชุม</th>
					<th colspan="3">ห้องพัก</th>
					<th rowspan="2">กิจกรรม</th>
					<th rowspan="2">หมายเลขติดต่อ</th>
				</tr>
				<tr>
					<th>เช้า</th>
					<th>กลางวัน</th>
					<th>เย็น</th>
					<th>เช้า</th>
					<th>บ่าย</th>
					<th>ดึก</th>
					<th>ชั้นเดียว</th>
					<th>สองเดียว</th>
					<th>หน่วยงาน</th>
					<th>ราคา</th>
					<th>จำนวน</th>
				</tr>
			</thead>
			<tbody>
				<?php
				$sum = 0;
				if($num>0){
					while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
						$sum = $sum + (int)$row['n_people'];
						$rowspan = "";
						$activities = $row['activities'] ? implode(", ", json_decode($row['activities'], true)) : "-";
						$meal_breakfast_price = $row['meal_breakfast_price'] ? $row['meal_breakfast_price'] : "-";
						$meal_lunch_price = $row['meal_lunch_price'] ? $row['meal_lunch_price'] : "-";
						$meal_dinner_price = $row['meal_dinner_price'] ? $row['meal_dinner_price'] : "-";
						$refreshment_morning_price = $row['refreshment_morning_price'] ? $row['refreshment_morning_price'] : "-";
						$refreshment_afternoon_price = $row['refreshment_afternoon_price'] ? $row['refreshment_afternoon_price'] : "-";
						$refreshment_evening_price = $row['refreshment_evening_price'] ? $row['refreshment_evening_price'] : "-";
						$single_decker_tram = $row['single_decker_tram'] ? $row['single_decker_tram'] : "-";
						$double_decker_tram = $row['double_decker_tram'] ? $row['double_decker_tram'] : "-";
						$meeting_room = $row['meeting_room_name'] ? $row['meeting_room_name'] : "-";
						$meeting_room .= $row['meeting_room_price'] ? " ราคา " . $row['meeting_room_price'] . " บาท" : "";
						$contact = $row['contact'] ? $row['contact'] : "-";
						$breakfast = $row['meal_breakfast_price'] ? $row['meal_breakfast_price'] : "-";
						$lunch = $row['meal_lunch_price'] ? $row['meal_lunch_price'] : "-";
						$dinner = $row['meal_dinner_price'] ? $row['meal_dinner_price'] : "-";
						$morning = $row['refreshment_morning_price'] ? $row['refreshment_morning_price'] : "-";
						$afternoon = $row['refreshment_afternoon_price'] ? $row['refreshment_afternoon_price'] : "-";
						$evening = $row['refreshment_evening_price'] ? $row['refreshment_evening_price'] : "-";
						$rooms = json_decode($row['rooms'], true);
						$room = NULL;
						if ($rooms){
							$rowspan = "rowspan=\"".count($rooms).'"';
							$room = array_shift($rooms);
						}else{
							$room['name'] = "-";
							$room['price'] = "-";
							$room['quantity'] = "-";
						}
						echo "
						<tr>
							<td {$rowspan} align=\"center\" valign=\"middle\">{$row['doc_number']}</td>
							<td {$rowspan} align=\"center\" valign=\"middle\">{$row['visit_date']}</td>
							<td {$rowspan} align=\"center\" valign=\"middle\">{$row['occupation']}</td>
							<td {$rowspan} align=\"center\" valign=\"middle\">{$row['n_people']}</td>
							<td {$rowspan} align=\"center\" valign=\"middle\">{$row['address']} {$row['district']} {$row['province']} {$row['country']}</td>
							<td {$rowspan} align=\"center\" valign=\"middle\">{$meal_breakfast_price}</td>
							<td {$rowspan} align=\"center\" valign=\"middle\">{$meal_lunch_price}</td>
							<td {$rowspan} align=\"center\" valign=\"middle\">{$meal_dinner_price}</td>
							<td {$rowspan} align=\"center\" valign=\"middle\">{$refreshment_morning_price}</td>
							<td {$rowspan} align=\"center\" valign=\"middle\">{$refreshment_afternoon_price}</td>
							<td {$rowspan} align=\"center\" valign=\"middle\">{$refreshment_evening_price}</td>
							<td {$rowspan} align=\"center\" valign=\"middle\">{$single_decker_tram}</td>
							<td {$rowspan} align=\"center\" valign=\"middle\">{$double_decker_tram}</td>
							<td {$rowspan} align=\"center\" valign=\"middle\">{$meeting_room}</td>
							<td align=\"center\" valign=\"middle\">{$room['name']}</td>
							<td align=\"center\" valign=\"middle\">{$room['price']}</td>
							<td align=\"center\" valign=\"middle\">{$room['quantity']}</td>
							<td {$rowspan} align=\"center\" valign=\"middle\">{$activities}</td>
							<td {$rowspan} align=\"center\" valign=\"middle\">{$row['contact']}</td>
						</tr>
						";
						while ($rooms){
							$room = array_shift($rooms);
							echo"
						<tr>
							<td align=\"center\" valign=\"middle\">{$room['name']}</td>
							<td align=\"center\" valign=\"middle\">{$room['price']}</td>
							<td align=\"center\" valign=\"middle\">{$room['quantity']}</td>
						</tr>
							";
						}
					}
				}
				?>
				<tr></tr>
				<tr>
					<td colspan="19" align="left">รายงานเมื่อวันที่ <?php echo date("d/m/Y");?> ทั้งหมด <?php echo number_format($num);?> รายการ จำนวน <?php echo $sum;?> คน</td>
				</tr>
			</tbody>
		</table>
	</div>
	<script>
		window.onbeforeunload = function(){return false;};
        setTimeout(function(){window.close();}, 10000);
        window.print();
	</script>
</body>
</html>