<?php 
	include_once 'api/config/variables.php';
	session_start();
?>
<html>
<head>
	<title>ศูนย์ศึกษาการพัฒนาเขาหินซ้อน อันเนื่องมาจากพระราชดำริ</title>
    <meta charset="UTF-8">
    <!-- <meta name="viewport" content="width=device-width, initial-scale=1.0"> -->
	<link rel="stylesheet" href="css/reset.css">
	<link rel="stylesheet" href="css/semantic.min.css">
	<link rel="stylesheet" href="css/style.css">
	<link rel="shortcut icon" href="favicon.png" type="image/x-icon">
	<script src="js/jquery-3.1.1.min.js"></script>
	<script src="js/semantic.min.js"></script>
	<script src="js/jquery.tablesort.min.js"></script>
	<script src="js/script.js"></script>
</head>
<?php
if (isset($_SESSION['user']) && $_SESSION['user'] != "") {
?>
<body>
	<div class="wrapper">
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
		<div class="main section">
				<div class="ui center aligned top attached segment">
					<h3 class="ui header">ข้อมูลคณะศึกษาดูงาน</h3>
					<div class="sub header">ศูนย์ศึกษาการพัฒนาเขาหินซ้อน อันเนื่องมาจากพระราชดำริ</div>
					<div class="ui basic segment">
						<div class="ui filter form">
							<div class="fields">
								<div class="one wide field">
										<div class="filter setting"><i class="filter icon"></i></div>
								</div>
								<div class="fifteen wide field">
									<div class="five fields">
										<div class=" field">
											<div class="month ui floating fluid dropdown labeled search icon button">
												<i class="calendar alternate outline icon"></i>
												<input type="hidden" name="month" onchange="applyFilter();">
												<span class="text">เดือน</span>
												<div class="menu">
													<div class="item" data-value="%">ทั้งหมด</div>
													<?php
													for($i=1;$i<13;$i++){
														echo "<div class=\"item\" data-value=\"{$i}\">{$months[$i-1]}</div>";
													}
													?>
												</div>
											</div>
										</div>
										<div class=" field">
											<div class="year ui floating fluid dropdown labeled search icon button">
												<i class="calendar outline icon"></i>
												<input type="hidden" name="year" onchange="applyFilter()">
												<span class="text">พ.ศ.</span>
												<div class="menu">
													<div class="item" data-value="%">ทั้งหมด</div>
													<?php
													foreach($years as $year){
														echo "<div class=\"item\" data-value=\"{$year}\">{$year}</div>";
													}
													?>
												</div>
											</div>
										</div>
										<div class=" field">
											<div class="occupation ui floating fluid dropdown labeled search icon button">
												<i class="user md icon"></i>
												<input type="hidden" name="occupation" onchange="applyFilter()">
												<span class="text">กลุ่มอาชีพ</span>
												<div class="menu">
													<div class="item" data-value="%">ทั้งหมด</div>
													<?php
													foreach($occupations as $occupation){
														echo "<div class=\"item\" data-value=\"{$occupation}\">{$occupation}</div>";
													}
													?>
												</div>
											</div>
										</div>
										<div class=" field">
											<div class="province ui floating fluid dropdown labeled search icon button">
												<i class="map marker alternate icon"></i>
												<input type="hidden" name="province" onchange="applyFilter()">
												<span class="text">จังหวัด</span>
												<div class="menu">
													<div class="item" data-value="%">ทั้งหมด</div>
													<?php
													foreach($provinces as $province){
														echo "<div class=\"item\">".$province."</div>";
													}
													?>
												</div>
											</div>
										</div>
										<div class=" field">
											<div class="country ui floating fluid dropdown labeled search icon button">
												<i class="globe alternate icon"></i>
												<input type="hidden" name="country" onchange="applyFilter()">
												<span class="text">ประเทศ</span>
												<div class="menu">
													<div class="item" data-value="%">ทั้งหมด</div>
													<?php
													foreach($countries as $country){
														echo "<div class=\"item\">".$country."</div>";
													}
													?>
												</div>
											</div>
										</div>	
										<div class=" field">
											<button class="ui fluid labeled icon button" onclick="resetFilter();"><i class="sync alternate icon"></i>แสดงทั้งหมด</button>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					
				</div>
		
				<div class="ui center aligned attached segment">
					<table class="<?php echo ($_SESSION['is_admin'])? "":"not_admin "; ?>record ui middle aligned center aligned small celled structured table">
						<thead>
							<tr>
								<th rowspan="2">หมายเลข</th>
								<th rowspan="2" class="default-sort">วันที่</th>
								<th rowspan="2">กลุ่มอาชีพ</th>
								<th rowspan="2">จำนวน (คน)</th>
								<th rowspan="2">หน่วยงาน / ที่อยู่</th>
								<th rowspan="2">ราคาอาหาร</th>
								<th colspan="2">รถรางดูงาน</th>
								<th rowspan="2">ห้องประชุม</th>
								<th colspan="3">ห้องพัก</th>
								<th rowspan="2">กิจกรรม</th>
								<th rowspan="2">หมายเลขติดต่อ</th>
								<th rowspan="2">แก้ไข/ลบ</th>
							</tr>
							<tr>
								<th>ชั้นเดียว</th>
								<th>สองเดียว</th>
								<th>หน่วยงาน</th>
								<th>ราคา</th>
								<th>จำนวน</th>
							</tr>
						</thead>
						<tbody id="records">
						</tbody>
					</table>
				</div>
				<div class="ui clearing secondary bottom attached segment">
					<a href="api/record/export.php" class="export excel ui right floated green labeled icon button">
						<i class="file excel outline icon"></i>
						ดาวน์โหลดรายงาน
					</a>
					<a href="api/record/print.php" target="_blank" class="print ui right floated blue labeled icon button">
						<i class="print icon"></i>
						พิมพ์รายงาน
					</a>
					<div class="ui image label">
						<img src="img/user.png">
						<?php echo $_SESSION['user']; ?>
						<div class="detail"><?php echo date("d",time())." {$thMonth} {$y}"; ?></div>
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
									<div class="ui fluid input left icon">
										<i class="calendar icon"></i>
										<input type="date" name="visit_date" placeholder="วันที่">
									</div>
							</div>
						</div>
						<div class="two fields">
							<div class="inline field">
								<div class="occupation ui fluid floating labeled icon search dropdown button">
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
						<div class="three fields">
							<div class="inline field">
								<div class="ui fluid labeled input">
									<div class="ui label">อำเภอ</div>
									<input type="text" name="district" placeholder="อำเภอ">
								</div>
							</div>
							<div class="inline field">
								<div class="province ui fluid floating labeled icon search dropdown button">
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
							<div class="inline field">
								<div class="country ui fluid floating labeled icon search dropdown button">
									<i class="globe alternate icon"></i>
									<input type="hidden" name="country">
									<span class="text">ประเทศ</span>
									<div class="menu">';
										foreach($countries as $country){
											echo "<div class=\"item\">".$country."</div>";
										}
									echo '
									</div>
								</div>
							</div>
						</div>
						<div class="three fields">
							<div class="inline field">
								<div class="ui fluid right labeled input">
									<label for="meal_breakfast_price" class="ui label">ราคาอาหาร เช้า</label>
									<input type="text" name="meal_breakfast_price" placeholder="ราคาอาหารเช้า">
									<div class="ui basic label">บาท</div>
								</div>
							</div>
							<div class="inline field">
								<div class="ui fluid right labeled input">
									<label for="meal_lunch_price" class="ui label">กลางวัน</label>
									<input type="text" name="meal_lunch_price" placeholder="ราคาอาหารกลางวัน">
									<div class="ui basic label">บาท</div>
								</div>
							</div>
							<div class="inline field">
								<div class="ui fluid right labeled input">
									<label for="meal_dinner_price" class="ui label">เย็น</label>
									<input type="text" name="meal_dinner_price" placeholder="ราคาอาหารเย็น">
									<div class="ui basic label">บาท</div>
								</div>
							</div>
						</div>
						<div class="three fields">
							<div class="inline field">
								<div class="ui fluid right labeled input">
									<label for="refreshment_morning_price" class="ui label">ราคาอาหารว่าง เช้า</label>
									<input type="text" name="refreshment_morning_price" placeholder="ราคาอาหารว่างเช้า">
									<div class="ui basic label">บาท</div>
								</div>
							</div>
							<div class="inline field">
								<div class="ui fluid right labeled input">
									<label for="refreshment_afternoon_price" class="ui label">บ่าย</label>
									<input type="text" name="refreshment_afternoon_price" placeholder="ราคาอาหารว่างบ่าย">
									<div class="ui basic label">บาท</div>
								</div>
							</div>
							<div class="inline field">
								<div class="ui fluid right labeled input">
									<label for="refreshment_evening_price" class="ui label">ดึก</label>
									<input type="text" name="refreshment_evening_price" placeholder="ราคาอาหารว่างดึก">
									<div class="ui basic label">บาท</div>
								</div>
							</div>
						</div>
						<div class="two fields">
							<div class="inline field">
								<div class="ui fluid right labeled input">
									<label for="single_decker_tram" class="ui label">จองรถรางดูงาน ชั้นเดียว</label>
									<input type="text" name="single_decker_tram" placeholder="จำนวนรถ">
									<div class="ui basic label">คัน</div>
								</div>
							</div>
							<div class="inline field">
								<div class="ui fluid right labeled input">
									<label for="double_decker_tram" class="ui label">สองชั้น</label>
									<input type="text" name="double_decker_tram" placeholder="จำนวนรถ">
									<div class="ui basic label">คัน</div>
								</div>
							</div>
						</div>
						<div class="rooms list">
							<div class="ui three fields room">
								<div class="inline field">
									<div class="room_name ui fluid floating labeled icon search dropdown button">
										<i class="home icon"></i>
										<input type="hidden" name="room_name">
										<span class="text">ห้องพัก</span>
										<div class="menu">
											<div class="item" data-value="">เลือกห้องพัก</div>';
											foreach($rooms as $room){
												echo "<div class=\"item\">".$room."</div>";
											}
										echo '
										</div>
									</div>
								</div>
								<div class="inline field">
									<div class="ui fluid right labeled input">
										<label for="room_price" class="ui label">ราคา</label>
										<input type="text" name="room_price" placeholder="ราคา">
										<div class="ui dropdown label">
											<div class="text">บาท/คน</div>
											<i class="dropdown icon"></i>
											<input type="hidden" name="room_price_tag" value="บาท/คน" onchange="updateRoomUnit(this);">
											<div class="menu">
												<div class="item">บาท/คน</div>
												<div class="item">บาท/หลัง</div>
												<div class="item">บาท/เต้นท์</div>
											</div>
										</div>
									</div>
								</div>
								<div class="inline field">
									<div class="ui fluid right labeled input">
										<label for="room_quantity" class="ui label">จำนวน</label>
										<input type="text" name="room_quantity" placeholder="จำนวน">
										<div class="ui basic label">คน</div>
									</div>
								</div>
							</div>
							<div class="ui dimmable fields room">
								<div class="ui inverted dimmer">
									<div class="content">
											<div class="ui compact basic button" onclick="addRoom(\'add\');">เพิ่มห้องพัก</div>
									</div>
								</div>
								<div class="disabled five wide field">
									<div class="room_name ui fluid floating labeled icon search dropdown button">
										<i class="home alternate icon"></i>
										<input type="hidden" name="room_name" value="">
										<span class="text">ห้องพัก</span>
										<div class="menu">
											<div class="item" data-value="">เลือกห้องพัก</div>;';
											foreach($rooms as $room){
												echo "<div class=\"item\">".$room."</div>";
											}
										echo '
										</div>
									</div>
								</div>
								<div class="disabled five wide field">
									<div class="ui fluid right labeled input">
										<label for="room_price" class="ui label">ราคา</label>
										<input type="text" name="room_price" placeholder="ราคา">
										<div class="ui dropdown label">
											<div class="text">บาท/คน</div>
											<i class="dropdown icon"></i>
											<input type="hidden" name="room_price_tag" value="บาท/คน" onchange="updateRoomUnit(this);">
											<div class="menu">
												<div class="item">บาท/คน</div>
												<div class="item">บาท/หลัง</div>
												<div class="item">บาท/เต้นท์</div>
											</div>
										</div>
									</div>
								</div>
								<div class="disabled four wide field">
									<div class="ui fluid right labeled input">
										<label for="room_quantity" class="ui label">จำนวน</label>
										<input type="text" name="room_quantity" placeholder="จำนวน">
										<div class="ui basic label">คน</div>
									</div>
								</div>
								<div class="two wide field">
									<div class="ui fluid basic button" onclick="removeRoom(this);">ลบ</div>
								</div>
							</div>
						</div>
						<div class="two fields">
							<div class="inline field">
								<div class="meeting_room_name ui fluid floating labeled icon search dropdown button">
									<i class="users icon"></i>
									<input type="hidden" name="meeting_room_name">
									<span class="text">ห้องประชุม</span>
									<div class="menu">
										<div class="item" data-value="">-</div>';
										foreach($meeting_rooms as $meeting_room){
											echo "<div class=\"item\">".$meeting_room."</div>";
										}
									echo '
									</div>
								</div>
							</div>
							<div class="inline field">
								<div class="ui fluid right labeled input">
									<label for="meeting_room_price" class="ui label">ราคา</label>
									<input type="text" name="meeting_room_price" placeholder="ราคา">
									<div class="ui basic label">บาท</div>
								</div>
							</div>
						</div>
						<div class="field">
							<div class="activities ui search multiple selection dropdown">
								<input name="activities" type="hidden">
								<i class="dropdown icon"></i>
								<div class="default text">กิจกรรม</div>
								<div class="menu">';
									foreach($activities as $activity){
										echo "<div class=\"item\">".$activity."</div>";
									}
								echo '
								</div>
					  		</div>	
						</div>
						<div class="field">
							<div class="ui fluid labeled input">
								<div class="ui label">หมายเลขติดต่อ</div>
								<input type="text" name="contact" placeholder="หมายเลขติดต่อ">
							</div>
						</div>
					</form>
					<div class="ui hidden divider"></div>
				</div>
				<div class="actions">
					<div class="ui cancel button">ยกเลิก</div>
					<div class="ui teal button" onclick="addRecord();">บันทึก</div>
				</div>
			</div>
			';
			
			echo '
			<div class="ui edit record modal">
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
									<div class="ui fluid input left icon">
										<i class="calendar icon"></i>
										<input type="date" name="visit_date" placeholder="วันที่">
									</div>
							</div>
						</div>
						<div class="two fields">
							<div class="inline field">
								<div class="occupation ui fluid floating labeled icon search dropdown button">
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
						<div class="three fields">
							<div class="inline field">
								<div class="ui fluid labeled input">
									<div class="ui label">อำเภอ</div>
									<input type="text" name="district" placeholder="อำเภอ">
								</div>
							</div>
							<div class="inline field">
								<div class="province ui fluid floating labeled icon search dropdown button">
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
							<div class="inline field">
								<div class="country ui fluid floating labeled icon search dropdown button">
									<i class="globe alternate icon"></i>
									<input type="hidden" name="country">
									<span class="text">ประเทศ</span>
									<div class="menu">';
										foreach($countries as $country){
											echo "<div class=\"item\">".$country."</div>";
										}
									echo '
									</div>
								</div>
							</div>
						</div>
						<div class="three fields">
							<div class="inline field">
								<div class="ui fluid right labeled input">
									<label for="meal_breakfast_price" class="ui label">ราคาอาหาร เช้า</label>
									<input type="text" name="meal_breakfast_price" placeholder="ราคาอาหารเช้า">
									<div class="ui basic label">บาท</div>
								</div>
							</div>
							<div class="inline field">
								<div class="ui fluid right labeled input">
									<label for="meal_lunch_price" class="ui label">กลางวัน</label>
									<input type="text" name="meal_lunch_price" placeholder="ราคาอาหารกลางวัน">
									<div class="ui basic label">บาท</div>
								</div>
							</div>
							<div class="inline field">
								<div class="ui fluid right labeled input">
									<label for="meal_dinner_price" class="ui label">เย็น</label>
									<input type="text" name="meal_dinner_price" placeholder="ราคาอาหารเย็น">
									<div class="ui basic label">บาท</div>
								</div>
							</div>
						</div>
						<div class="three fields">
							<div class="inline field">
								<div class="ui fluid right labeled input">
									<label for="refreshment_morning_price" class="ui label">ราคาอาหารว่าง เช้า</label>
									<input type="text" name="refreshment_morning_price" placeholder="ราคาอาหารว่างเช้า">
									<div class="ui basic label">บาท</div>
								</div>
							</div>
							<div class="inline field">
								<div class="ui fluid right labeled input">
									<label for="refreshment_afternoon_price" class="ui label">บ่าย</label>
									<input type="text" name="refreshment_afternoon_price" placeholder="ราคาอาหารว่างบ่าย">
									<div class="ui basic label">บาท</div>
								</div>
							</div>
							<div class="inline field">
								<div class="ui fluid right labeled input">
									<label for="refreshment_evening_price" class="ui label">ดึก</label>
									<input type="text" name="refreshment_evening_price" placeholder="ราคาอาหารว่างดึก">
									<div class="ui basic label">บาท</div>
								</div>
							</div>
						</div>
						<div class="two fields">
							<div class="inline field">
								<div class="ui fluid right labeled input">
									<label for="single_decker_tram" class="ui label">จองรถรางดูงาน ชั้นเดียว</label>
									<input type="text" name="single_decker_tram" placeholder="จำนวนรถ">
									<div class="ui basic label">คัน</div>
								</div>
							</div>
							<div class="inline field">
								<div class="ui fluid right labeled input">
									<label for="double_decker_tram" class="ui label">สองชั้น</label>
									<input type="text" name="double_decker_tram" placeholder="จำนวนรถ">
									<div class="ui basic label">คัน</div>
								</div>
							</div>
						</div>
						<div class="rooms list">
							<div class="ui three fields room">
								<div class="inline field">
									<div class="room_name ui fluid floating labeled icon search dropdown button">
										<i class="home icon"></i>
										<input type="hidden" name="room_name">
										<span class="text">ห้องพัก</span>
										<div class="menu">
											<div class="item" data-value="">เลือกห้องพัก</div>';
											foreach($rooms as $room){
												echo "<div class=\"item\">".$room."</div>";
											}
										echo '
										</div>
									</div>
								</div>
								<div class="inline field">
									<div class="ui fluid right labeled input">
										<label for="room_price" class="ui label">ราคา</label>
										<input type="text" name="room_price" placeholder="ราคา">
										<div class="room_price_tag ui dropdown label">
											<div class="text">บาท/คน</div>
											<i class="dropdown icon"></i>
											<input type="hidden" name="room_price_tag" value="บาท/คน" onchange="updateRoomUnit(this);">
											<div class="menu">
												<div class="item">บาท/คน</div>
												<div class="item">บาท/หลัง</div>
												<div class="item">บาท/เต้นท์</div>
											</div>
										</div>
									</div>
								</div>
								<div class="inline field">
									<div class="ui fluid right labeled input">
										<label for="room_quantity" class="ui label">จำนวน</label>
										<input type="text" name="room_quantity" placeholder="จำนวน">
										<div class="ui basic label">คน</div>
									</div>
								</div>
							</div>
							<div class="ui dimmable fields room">
								<div class="ui inverted dimmer">
									<div class="content">
											<div class="ui compact basic button" onclick="addRoom(\'edit\');">เพิ่มห้องพัก</div>
									</div>
								</div>
								<div class="disabled five wide field">
									<div class="room_name ui fluid floating labeled icon search dropdown button">
										<i class="home alternate icon"></i>
										<input type="hidden" name="room_name" value="">
										<span class="text">ห้องพัก</span>
										<div class="menu">
											<div class="item" data-value="">เลือกห้องพัก</div>;';
											foreach($rooms as $room){
												echo "<div class=\"item\">".$room."</div>";
											}
										echo '
										</div>
									</div>
								</div>
								<div class="disabled five wide field">
									<div class="ui fluid right labeled input">
										<label for="room_price" class="ui label">ราคา</label>
										<input type="text" name="room_price" placeholder="ราคา">
										<div class="room_price_tag ui dropdown label">
											<div class="text">บาท/คน</div>
											<i class="dropdown icon"></i>
											<input type="hidden" name="room_price_tag" value="บาท/คน" onchange="updateRoomUnit(this);">
											<div class="menu">
												<div class="item">บาท/คน</div>
												<div class="item">บาท/หลัง</div>
												<div class="item">บาท/เต้นท์</div>
											</div>
										</div>
									</div>
								</div>
								<div class="disabled four wide field">
									<div class="ui fluid right labeled input">
										<label for="room_quantity" class="ui label">จำนวน</label>
										<input type="text" name="room_quantity" placeholder="จำนวน">
										<div class="ui basic label">คน</div>
									</div>
								</div>
								<div class="two wide field">
									<div class="ui fluid basic button" onclick="removeRoom(this);">ลบ</div>
								</div>
							</div>
						</div>
						<div class="two fields">
							<div class="inline field">
								<div class="meeting_room_name ui fluid floating labeled icon search dropdown button">
									<i class="users icon"></i>
									<input type="hidden" name="meeting_room_name">
									<span class="text">ห้องประชุม</span>
									<div class="menu">
										<div class="item" data-value="">-</div>';
										foreach($meeting_rooms as $meeting_room){
											echo "<div class=\"item\">".$meeting_room."</div>";
										}
									echo '
									</div>
								</div>
							</div>
							<div class="inline field">
								<div class="ui fluid right labeled input">
									<label for="meeting_room_price" class="ui label">ราคา</label>
									<input type="text" name="meeting_room_price" placeholder="ราคา">
									<div class="ui basic label">บาท</div>
								</div>
							</div>
						</div>
						<div class="field">
							<div class="activities ui search multiple selection dropdown">
								<input name="activities" type="hidden">
								<i class="dropdown icon"></i>
								<div class="default text">กิจกรรม</div>
								<div class="menu">';
									foreach($activities as $activity){
										echo "<div class=\"item\">".$activity."</div>";
									}
								echo '
								</div>
					  		</div>	
						</div>
						<div class="field">
							<div class="ui fluid labeled input">
								<div class="ui label">หมายเลขติดต่อ</div>
								<input type="text" name="contact" placeholder="หมายเลขติดต่อ">
							</div>
						</div>
					</form>
					<div class="ui hidden divider"></div>
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
					<img class="ui centered tiny circular image" src="img/logo.jpg">
					<div class="ui center aligned header">
						ระบบบันทึกข้อมูลคณะศึกษาดูงาน
						<div class="ui sub header">
							ศูนย์ศึกษาการพัฒนาเขาหินซ้อน อันเนื่องมาจากพระราชดำริ
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