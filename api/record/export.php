<?php
$ExcelFileName='export_'.date('d-m-Y').'.xls';
header("Content-Type: application/x-msexcel; name=\"$ExcelFileName\"");
header("Content-Disposition: inline; filename=\"$ExcelFileName\"");
header("Pragma:no-cache");

include_once '../config/database.php';
include_once '../record.php';

$database = new Database();
$db = $database->getConnection();

$record = new Record($db);
$month = isset($_GET['month']) ? $_GET['month'] : "%";
$year = isset($_GET['year']) ? $_GET['year'] : "%";
$occupation = isset($_GET['occupation']) ? $_GET['occupation'] : "%";
$province = isset($_GET['province']) ? $_GET['province'] : "%";
$stmt = $record->search($month, $year, $occupation, $province);
$num = $stmt->rowCount();
?>
<html xmlns:o="urn:schemas-microsoft-com:office:office"xmlns:x="urn:schemas-microsoft-com:office:excel"xmlns="http://www.w3.org/TR/REC-html40">
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>
	<div id="SiXhEaD_Excel" align=center x:publishsource="Excel">
		<table x:str>
			<thead>
				<tr>
					<td colspan="12" align="center"><h1>รางานข้อมูลคณะศึกษาดูงาน</h1></td>
				</tr>
				<tr>
					<td colspan="12" align="center"><h3>ศูนย์การศึกษาพัฒนาเขาหินซ้อนอันเนื่องมาจากพระราชดำริ</h3></td>
				</tr>
				<tr></tr>
				<tr>
					<th>หมายเลข</th>
					<th>วันที่</th>
					<th>กลุ่มอาชีพ</th>
					<th>จำนวน (คน)</th>
					<th>หน่วยงาน / ที่อยู่</th>
					<th>ราคาอาหาร (บาท)</th>
					<th>จำนวน (คน)</th>
					<th>ที่พักรายบุคคล</th>
					<th>จำนวน (ห้อง)</th>
					<th>ที่พักกลุ่ม</th>
					<th>จำนวน (ห้อง)</th>
					<th>ห้องประชุม</th>
				</tr>
			</thead>
			<tbody>
				<?php
				$sum = 0;
				if($num>0){
					while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
						$sum = $sum + $row['n_people'];
						echo "
						<tr>
							<td align=\"center\" valign=\"middle\">{$row['doc_number']}</td>
							<td align=\"center\" valign=\"middle\">{$row['visit_date']}</td>
							<td align=\"center\" valign=\"middle\">{$row['occupation']}</td>
							<td align=\"center\" valign=\"middle\">{$row['n_people']}</td>
							<td align=\"center\" valign=\"middle\">{$row['address']} อ.{$row['district']} จ.{$row['province']}</td>
							<td align=\"center\" valign=\"middle\">{$row['meal_price']}</td>
							<td align=\"center\" valign=\"middle\">{$row['meal_quantity']}</td>
							<td align=\"center\" valign=\"middle\">{$row['personal_room']}</td>
							<td align=\"center\" valign=\"middle\">{$row['personal_room_quantity']}</td>
							<td align=\"center\" valign=\"middle\">{$row['group_room']}</td>
							<td align=\"center\" valign=\"middle\">{$row['group_room_quantity']}</td>
							<td align=\"center\" valign=\"middle\">{$row['meeting_room']}</td>
						</tr>
						";
					}
				}
				?>
				<tr></tr>
				<tr>
					<td colspan="12" align="left">รายงานเมื่อวันที่ <?php echo date("d/m/Y");?> ทั้งหมด <?php echo number_format($num);?> รายการ จำนวน <?php echo $sum;?> คน</td>
				</tr>
			</tbody>
		</table>
	</div>
	<script>
		window.onbeforeunload = function(){return false;};
		setTimeout(function(){window.close();}, 10000);
	</script>
</body>
</html>