<?php 
	include_once 'api/config/variables.php';
	session_start();
?>
<html>
<head>
	<title>ศูนย์การพัฒนาเขาหินซ้อนอันเนื่องมาจากพระราชดำริ</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="css/reset.css">
	<link rel="stylesheet" href="css/semantic.min.css">
	<link rel="stylesheet" href="css/calendar.min.css">
	<link rel="stylesheet" href="css/style.css">
	<link rel="shortcut icon" href="img/favicon.png" type="image/x-icon">
	<script src="js/jquery-3.1.1.min.js"></script>
	<script src="js/semantic.min.js"></script>
	<script src="js/calendar.min.js"></script>
	<script src="js/script.js"></script>
</head>
<?php
if (isset($_SESSION['user']) && $_SESSION['user'] != "") {
?>
<body>
	<div class="ui padded grid">
		<div class="navbar">
			<div class="menu">
				<a class="item">
					<i class="file alternate outline icon"></i>
				</a>
				<?php if ($_SESSION['is_admin']) { ?>
				<a class="add record item" onclick="addRecordModal();">
					<i class="edit outline icon"></i>
				</a>
				<a class="manage user item" onclick="manageUserModal();">
					<i class="user outline icon"></i>
				</a>
				<?php } ?>
				<a class="item" onclick="logout();">
					<i class="power on alternate icon"></i>
				</a>
			</div>
		</div>
		<div class="one wide column">		
		</div>
		<div class="fifteen wide column">
			<div class="ui raised segments">
				<div class="ui center aligned segment">
					<h3 class="ui header">ข้อมูลคณะศึกษาดูงาน</h3>
					<div class="sub header">ศูนย์การพัฒนาเขาหินซ้อนอันเนื่องมาจากพระราชดำริ</div>
				</div>
				<div class="ui center aligned segment">
					<div class="ui filter form">
						<div class="equal width fields">
							<div class="one wide field">
									<div class="filter setting"><i class="filter icon"></i></div>
							</div>
							<div class="three wide field">
								<div class="month ui floating fluid dropdown labeled search icon button">
									<i class="calendar alternate outline icon"></i>
									<span class="text">เดือน</span>
									<div class="menu">
										<div class="item" data-value="*">ทั้งหมด</div>
										<?php
										foreach($months as $month){
											echo "<div class=\"item\" data-value=\"{$month}\">{$month}</div>";
										}
										?>
									</div>
								</div>
							</div>
							<div class="three wide field">
								<div class="year ui floating fluid dropdown labeled search icon button">
									<i class="calendar outline icon"></i>
									<span class="text">พ.ศ.</span>
									<div class="menu">
										<div class="item" data-value="*">ทั้งหมด</div>
										<?php
										foreach($years as $year){
											echo "<div class=\"item\" data-value=\"{$year}\">{$year}</div>";
										}
										?>
									</div>
								</div>
							</div>
							<div class="three wide field">
								<div class="occupation ui floating fluid dropdown labeled search icon button">
									<i class="user md icon"></i>
									<span class="text">กลุ่มอาชีพ</span>
									<div class="menu">
										<div class="item" data-value="*">ทั้งหมด</div>
										<?php
										foreach($occupations as $occupation){
											echo "<div class=\"item\" data-value=\"{$occupation}\">{$occupation}</div>";
										}
										?>
									</div>
								</div>
							</div>
							<div class="three wide field">
								<div class="province ui floating fluid dropdown labeled search icon button">
									<i class="map marker alternate icon"></i>
									<span class="text">จังหวัด</span>
									<div class="menu">
										<?php
										foreach($provinces as $province){
											echo "<div class=\"item\">".$province."</div>";
										}
										?>
									</div>
								</div>
							</div>	
							<div class="three wide field">
								<button class="ui fluid labeled icon button"><i class="sync alternate icon"></i>แสดงทั้งหมด</button>
							</div>
						</div>
					</div>
					<table class="ui center aligned celled striped table">
						<thead>
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
						<tbody id="records">
						</tbody>
					</table>
				</div>
				<div class="ui clearing secondary segment">
					<div class="ui right floated green labeled icon button">
						<i class="file excel outline icon"></i>
						ดาวน์โหลดรายงาน
					</div>
					<div class="ui right floated blue labeled icon button">
						<i class="print icon"></i>
						พิมพ์รายงาน
					</div>
					<div class="ui image label">
						<img src="img/user.png">
						<?php echo $_SESSION['user']; ?>
						<div class="detail"><?php echo date("d",time())." {$thMonth} {$y}"; ?></div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php
		if ($_SESSION['is_admin']) {
			echo '
			<div class="ui add record modal">
				<div class="ui center aligned header">บันทึกข้อมูลคณะศึกษาดูงาน</div>
				<div class="scrolling content">
					<form class="ui form">
						<div class="two fields">
							<div class="inline field">
								<div class="ui fluid labeled input">
									<div class="ui label">หมายเลขเอกสาร</div>
									<input type="text" name="doc_number" placeholder="หมายเลขเอกสาร">
								</div>
							</div>
							<div class="inline field">
								<div class="ui calendar" id="datetimepicker">
									<div class="ui fluid input left icon">
										<i class="calendar icon"></i>
										<input type="text" name="visit_date" placeholder="วันที่">
									</div>
								</div>
							</div>
						</div>
						<div class="two fields">
							<div class="inline field">
								<div class="ui fluid floating labeled icon search dropdown button">
									<i class="user md alternate icon"></i>
									<input type="hidden" name="occupation">
									<span class="text">กลุ่มอาชีพ</span>
									<div class="menu">';
										foreach($occupations as $occupation){
											echo "<div class=\"item\">".$occupation."</div>";
										}
									echo '
									</div>
								</div>
							</div>
							<div class="inline field">
								<div class="ui fluid right labeled input">
									<label for="n_people" class="ui label">จำนวน</label>
									<input type="text" name="n_people" placeholder="จำนวน">
									<div class="ui basic label">คน</div>
								</div>
							</div>
						</div>
						<div class="ui field">
							<div class="ui fluid labeled input">
								<div class="ui label">หน่วยงาน/ที่อยู่</div>
								<input type="text" name="address" placeholder="หน่วยงาน/ที่อยู่">
							</div>
						</div>
						<div class="two fields">
							<div class="inline field">
								<div class="ui fluid labeled input">
									<div class="ui label">อำเภอ</div>
									<input type="text" name="distric" placeholder="อำเภอ">
								</div>
							</div>
							<div class="inline field">
								<div class="ui fluid floating labeled icon search dropdown button">
									<i class="map marker alternate icon"></i>
									<input type="hidden" name="province">
									<span class="text">จังหวัด</span>
									<div class="menu">';
										foreach($provinces as $province){
											echo "<div class=\"item\">".$province."</div>";
										}
									echo '
									</div>
								</div>
							</div>
						</div>
						<div class="two fields">
							<div class="inline field">
								<div class="ui fluid right labeled input">
									<label for="meal_price" class="ui label">ราคาอาหารมื้อหลัก</label>
									<input type="text" name="meal_price" placeholder="ราคาอาหาร">
									<div class="ui basic label">บาท</div>
								</div>
							</div>
							<div class="inline field">
								<div class="ui fluid right labeled input">
									<label for="meal_quantity" class="ui label">จำนวน</label>
									<input type="text" name="meal_quantity" placeholder="จำนวน">
									<div class="ui basic label">ที่</div>
								</div>
							</div>
						</div>
						<div class="two fields">
							<div class="inline field">
								<div class="ui fluid labeled input">
									<div class="ui label">ห้องพักรายบุคคล</div>
									<input type="text" name="personal_room" placeholder="ห้องพักรายบุคคล">
								</div>
							</div>
							<div class="inline field">
								<div class="ui fluid right labeled input">
									<label for="personal_room_quantity" class="ui label">จำนวน</label>
									<input type="text" name="personal_room_quantity" placeholder="จำนวน">
									<div class="ui basic label">ห้อง</div>
								</div>
							</div>
						</div>
						<div class="two fields">
							<div class="inline field">
								<div class="ui fluid labeled input">
									<div class="ui label">ห้องพักกลุ่ม</div>
									<input type="text" name="group_room" placeholder="ห้องพักกลุ่ม">
								</div>
							</div>
							<div class="inline field">
								<div class="ui fluid right labeled input">
									<label for="group_room_quantity" class="ui label">จำนวน</label>
									<input type="text" name="group_room_quantity" placeholder="จำนวน">
									<div class="ui basic label">ห้อง</div>
								</div>
							</div>
						</div>
						<div class="ui field">
							<div class="ui fluid labeled input">
								<div class="ui label">ห้องประชุม</div>
								<input type="text" name="meeting_room" placeholder="ห้องประชุม">
							</div>
						</div>
					</form>
				</div>
				<div class="actions">
					<div class="ui cancel button">ยกเลิก</div>
					<div class="ui teal button" onclick="addRecord();">บันทึก</div>
				</div>
			</div>
			';

			echo '
			<div class="ui manage user modal">
				<div class="ui center aligned header">จัดการข้อมูลผู้ใช้และรหัสผ่าน</div>
				<div class="scrolling content">
					<table class="user ui center aligned striped padded celled table">
						<thead>
							<tr>
								<th>ชื่อผู้ใช้</th>
								<th>รหัสผ่าน</th>
								<th>ผู้ดูแลระบบ?</th>
								<th>แก้ไข/ลบ</th>
							</tr>
						</thead>
						<tbody>
							
						</tbody>
						<tfoot>
							<tr data-id="new">
								<th>
									<div class="field">
										<div class="ui input">
											<input type="type" name="username" value="" placeholder="ชื่อผู้ใช้">
										</div>
									</div>		
								</th>
								<th>
									<div class="ui icon input">
										<i class="eye icon"></i>
										<input type="password" name="password" value="" placeholder="รหัสผ่าน">
									</div>		
								</th>
								<th class="middle aligned">
									<div class="is_admin ui toggle checkbox">
										<input type="checkbox" name="is_admin" tabindex="0" class="hidden">
									</div>
								</th>
								<th colspan="4">
									<div class="ui blue fluid labeled icon button" onclick="newUser();">
										<i class="user plus icon"></i>
										เพิ่มผู้ใช้
									</div>
								</th>
							</tr>
						</tfoot>
					</table>
				</div>
				<div class="actions">
					<div class="ui cancel button">ยกเลิก</div>
				</div>
			</div>

			<div class="delete user mini modal">
				<div class="ui center aligned header">ลบบัญชีผู้ใช้ ?</div>
				<div class="content">
					คุณต้องการลบชื่อผู้ใช้หรือไม่
				</div>
				<div class="actions">
					<div class="ui cancel button">ไม่</div>
					<div class="ui teal ok button">ลบ</div>
				</div>
			</div>
			';
		}
	?>	

</body>
<?php
}else{
?>
<body class="login">
	<div class="ui middle aligned center aligned grid">
		<div class="center aligned three column row">
			<div class="column">
				<div class="ui raised very padded compact segment">
					<img class="ui centered tiny circular image" src="img/user.png">
					<div class="ui center aligned header">
						ระบบบันทึกข้อมูลคณะศึกษาดูงาน
						<div class="ui sub header">
							ศูนย์การพัฒนาเขาหินซ้อนอันเนื่องมาจากพระราชดำริ
						</div>
					</div>
					<div class="ui hidden divider"></div>
					<form action="api/user/login.php" method="POST" class="ui fluid login form">
						<div class="field">
							<div class="ui fluid big transparent left icon input">
								<input name ="username" type="text" placeholder="ชื่อผู้ใช้">
								<i class="user outline icon"></i>
							</div>
						</div>
						<div class="ui divider"></div>
						<div class="field">
							<div class="ui fluid big transparent left icon input">
								<input name ="password" type="password" placeholder="รหัสผ่าน" autocomplete="false">
								<i class="asterisk icon"></i>
							</div>
						</div>
						<div class="ui divider"></div>
						<div class="ui hidden divider"></div>
						<div class="field">
							<button class="ui big fluid teal button" type="submit">เข้าสู่ระบบ</button>
						</div>
						<div class="ui hidden divider"></div>
					</form>
					<?php if (isset($_SESSION['msg']) && $_SESSION['msg'] != "") {
						echo "<div class=\"ui error message\">".$_SESSION['msg']."</div>";
						$_SESSION['msg'] = "";
					}?>
				</div>
			</div>
		</div>
	</div>
</body>	
<?php
}
?>
</html>