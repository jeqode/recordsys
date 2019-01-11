months = ['มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษพาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'];
$(function(){
	$('.ui.dropdown')
  		.dropdown()
	;
	$('.ui.checkbox')
		.checkbox()
	;
	applyFilter();
	$('.ui.modal')
		.modal({
			blurring: true,
			closable: false,
			allowMultiple: true
		});
});

function formatDate(date) {
	var d = new Date(date),
		month = '' + (d.getMonth() + 1),
		day = '' + d.getDate(),
		year = d.getFullYear();

	if (month.length < 2) month = '0' + month;
	if (day.length < 2) day = '0' + day;

	return [year, month, day].join('-');
}

function logout(){
	$.ajax({
		type: "POST",
		url: './api/user/logout.php',
		dataType: 'json',
		success: function(data) {
			location.reload();
		}
	});
	location.reload();
}

function loadReports(filter){
	$.ajax({
		url: "./api/record/search.php",
		dataType: "JSON",
		type: "POST",
		data: {
			'month': filter.month,
			'year': filter.year,
			'occupation': filter.occupation,
			'province': filter.province
		},
		success: function(data, textStatus, jQxhr){
			if(data['data'] === undefined || data['data'].lenght == 0){
				$('#records').html("<tr><td colspan=\"12\" class=\"ui center aligned item\">ไม่พบข้อมูล</td></tr>");
			}else{
				$('#records').html("");
				$.each(data['data'], function(index, record) {
					$('#records').append(`
					<tr>
						<td>${record['doc_number']}</td>
						<td>${record['visit_date']}</td>
						<td>${record['occupation']}</td>
						<td>${record['n_people']}</td>
						<td>${record['address']} ${record['district']} ${record['province']}</td>
						<td>${record['meal_price']}</td>
						<td>${record['meal_quantity']}</td>
						<td>${record['personal_room']}</td>
						<td>${record['personal_room_quantity']}</td>
						<td>${record['group_room']}</td>
						<td>${record['group_room_quantity']}</td>
						<td>${record['meeting_room']}</td>
						<td class="middle aligned">
							<div class ="ui buttons">
								<div class="ui teal icon button" onclick="readRecordByRecordTime('${record['record_time']}');"><i class="edit outline icon"></i></div>
								<div class="ui red icon button" onclick="if(confirm('ยืนยันการลบข้อมูล ?'))deleteRecordByRecordTime('${record['record_time']}');"><i class="trash alternate outline icon"></i></div>
							</div>
						</td>
					</tr>
					`);
				});
			}   
		},
		error: function(jqXHR, exception) {
			if (jqXHR.status === 0) {
				alert('Not connect.\n Verify Network.');
			} else if (jqXHR.status == 404) {
				alert('Requested page not found. [404]');
			} else if (jqXHR.status == 500) {
				alert('Internal Server Error [500].');
			} else if (exception === 'parsererror') {
				alert('Requested JSON parse failed.');
			} else if (exception === 'timeout') {
				alert('Time out error.');
			} else if (exception === 'abort') {
				alert('Ajax request aborted.');
			} else {
				alert('Uncaught Error.\n' + jqXHR.responseText);
			}
		}
	});	
}

function applyFilter(){
	filter = new Object();
	filter.month = $('.filter [name="month"]').val()!="" ? $('.filter [name="month"]').val() : "%" ;
	filter.year = $.isNumeric($('.filter [name="year"]').val())? $('.filter [name="year"]').val() - 543 : "%";
	filter.occupation = $('.filter [name="occupation"]').val() != "" ? $('.filter [name="occupation"]').val() : "%" ;
	filter.province = $('.filter [name="province"]').val() != "" ? $('.filter [name="province"]').val() : "%" ;
	loadReports(filter);
	$('a.export.excel.button').attr("href","api/record/export.php?month="+filter.month+"&year="+filter.year+"&occupation="+filter.occupation+"&province="+filter.province);
	$('a.print.button').attr("href","api/record/print.php?month="+filter.month+"&year="+filter.year+"&occupation="+filter.occupation+"&province="+filter.province);
}

function resetFilter(){
	$('.filter .dropdown').dropdown("restore defaults");
	applyFilter();
}

function addRecordModal(){
	$('.ui.add.record.modal').modal('show');
	today = new Date();
	$('.add.record [name="visit_date"]').val(formatDate(today));
	$('.add.record [name="doc_number"]').val("");
	$('.add.record #datetimepicker').calendar("set date", new Date(today.getFullYear(), today.getMonth(), today.getDate()));
	$('.add.record .occupation.dropdown').dropdown("restore defaults");
	$('.add.record [name="address"]').val("");
	$('.add.record [name="district"]').val("");
	$('.add.record .province.dropdown').dropdown("restore defaults");
	$('.add.record [name="n_people"]').val("");
	$('.add.record [name="meal_price"]').val("");
	$('.add.record [name="meal_quantity"]').val("");
	$('.add.record [name="personal_room"]').val("");
	$('.add.record [name="personal_room_quantity"]').val("");
	$('.add.record [name="group_room"]').val("");
	$('.add.record [name="group_room_quantity"]').val("");
	$('.add.record [name="meeting_room"]').val("");
}

function addRecord(){
	doc_number = $('.add.record [name="doc_number"]').val();
	visit_date = $('.add.record [name="visit_date"]').val();
	occupation = $('.add.record [name="occupation"]').val();
	address = $('.add.record [name="address"]').val();
	district = $('.add.record [name="district"]').val();
	province = $('.add.record [name="province"]').val();
	n_people = $('.add.record [name="n_people"]').val();
	meal_price = $('.add.record [name="meal_price"]').val();
	meal_quantity = $('.add.record [name="meal_quantity"]').val();
	personal_room = $('.add.record [name="personal_room"]').val();
	personal_room_quantity = $('.add.record [name="personal_room_quantity"]').val();
	group_room = $('.add.record [name="group_room"]').val();
	group_room_quantity = $('.add.record [name="group_room_quantity"]').val();
	meeting_room = $('.add.record [name="meeting_room"]').val();

	$.ajax({
		url: './api/record/new.php',
		dataType: 'JSON',
		type: 'POST',
		data: { 'doc_number': doc_number,
				'visit_date': visit_date,
				'occupation': occupation,
				'n_people': n_people,
				'address': address,
				'district': district,
				'province': province,
				'meal_price': meal_price,
				'meal_quantity': meal_quantity,
				'personal_room': personal_room,
				'personal_room_quantity': personal_room_quantity,
				'group_room': group_room,
				'group_room_quantity': group_room_quantity,
				'meeting_room': meeting_room
			},
		success: function( data, textStatus, jQxhr ){
			console.log(data['message']);
			$('.add.record.modal').modal("hide");
			applyFilter();
		},
		error: function(jqXHR, exception) {
			if (jqXHR.status === 0) {
				alert('Not connect.\n Verify Network.');
			} else if (jqXHR.status == 404) {
				alert('Requested page not found. [404]');
			} else if (jqXHR.status == 500) {
				alert('Internal Server Error [500].');
			} else if (exception === 'parsererror') {
				alert('Requested JSON parse failed.');
			} else if (exception === 'timeout') {
				alert('Time out error.');
			} else if (exception === 'abort') {
				alert('Ajax request aborted.');
			} else {
				alert('Uncaught Error.\n' + jqXHR.responseText);
			}
		}
	});
}

function readRecordByRecordTime(time){
	$.ajax({
		url: "./api/record/read_one.php",
		dataType: "JSON",
		type: "POST",
		data: {
			'record_time': time
		},
		success: function(data, textStatus, jQxhr){
			$('.edit.record [name="doc_number"]').val(data['doc_number']);
			$('.edit.record [name="visit_date"]').val(data['visit_date']);
			$('.edit.record .occupation.dropdown').dropdown("set selected", data['occupation']);
			$('.edit.record [name="address"]').val(data['address']);
			$('.edit.record [name="district"]').val(data['district']);
			$('.edit.record .province.dropdown').dropdown("set selected", data['province']);
			$('.edit.record [name="n_people"]').val(data['n_people']);
			$('.edit.record [name="meal_price"]').val(data['meal_price']);
			$('.edit.record [name="meal_quantity"]').val(data['meal_quantity']);
			$('.edit.record [name="personal_room"]').val(data['personal_room']);
			$('.edit.record [name="personal_room_quantity"]').val(data['personal_room_quantity']);
			$('.edit.record [name="group_room"]').val(data['group_room']);
			$('.edit.record [name="group_room_quantity"]').val(data['group_room_quantity']);
			$('.edit.record [name="meeting_room"]').val(data['meeting_room']);
			$('.edit.record .teal.button').attr("onclick", "editRecord('"+time+"');");
			editRecordModal();
		},
		error: function(jqXHR, exception) {
			if (jqXHR.status === 0) {
				alert('Not connect.\n Verify Network.');
			} else if (jqXHR.status == 404) {
				alert('Requested page not found. [404]');
			} else if (jqXHR.status == 500) {
				alert('Internal Server Error [500].');
			} else if (exception === 'parsererror') {
				alert('Requested JSON parse failed.');
			} else if (exception === 'timeout') {
				alert('Time out error.');
			} else if (exception === 'abort') {
				alert('Ajax request aborted.');
			} else {
				alert('Uncaught Error.\n' + jqXHR.responseText);
			}
		}
	});
}

function editRecordModal(){
	$('.ui.edit.record.modal').modal('show');
}

function editRecord(time){
	doc_number = $('.edit.record [name="doc_number"]').val();
	visit_date = $('.edit.record [name="visit_date"]').val();
	occupation = $('.edit.record [name="occupation"]').val();
	address = $('.edit.record [name="address"]').val();
	district = $('.edit.record [name="district"]').val();
	province = $('.edit.record [name="province"]').val();
	n_people = $('.edit.record [name="n_people"]').val();
	meal_price = $('.edit.record [name="meal_price"]').val();
	meal_quantity = $('.edit.record [name="meal_quantity"]').val();
	personal_room = $('.edit.record [name="personal_room"]').val();
	personal_room_quantity = $('.edit.record [name="personal_room_quantity"]').val();
	group_room = $('.edit.record [name="group_room"]').val();
	group_room_quantity = $('.edit.record [name="group_room_quantity"]').val();
	meeting_room = $('.edit.record [name="meeting_room"]').val();

	$.ajax({
		url: './api/record/edit_one.php',
		dataType: 'text',
		type: 'POST',
		data: { 'record_time': time,
				'doc_number': doc_number,
				'visit_date': visit_date,
				'occupation': occupation,
				'n_people': n_people,
				'address': address,
				'district': district,
				'province': province,
				'meal_price': meal_price,
				'meal_quantity': meal_quantity,
				'personal_room': personal_room,
				'personal_room_quantity': personal_room_quantity,
				'group_room': group_room,
				'group_room_quantity': group_room_quantity,
				'meeting_room': meeting_room
			},
		success: function( data, textStatus, jQxhr ){
			console(data);
			$('.edit.record.modal').modal("hide");
			applyFilter();
		},
		error: function(jqXHR, exception) {
			console.log(exception);
			if (jqXHR.status === 0) {
				alert('Not connect.\n Verify Network.');
			} else if (jqXHR.status == 404) {
				alert('Requested page not found. [404]');
			} else if (jqXHR.status == 500) {
				alert('Internal Server Error [500].');
			} else if (exception === 'parsererror') {
				alert('Requested JSON parse failed.');
			} else if (exception === 'timeout') {
				alert('Time out error.');
			} else if (exception === 'abort') {
				alert('Ajax request aborted.');
			} else {
				alert('Uncaught Error.\n' + jqXHR.responseText);
			}
		}
	});
}

function deleteRecordByRecordTime(time){
	$.ajax({
		url: './api/record/delete.php',
		dataType: 'JSON',
		type: 'POST',
		data: { 'record_time': time},
		success: function( data, textStatus, jQxhr ){
			console.log(data['message']);
			applyFilter();
		},
		error: function(jqXHR, exception) {
			if (jqXHR.status === 0) {
				alert('Not connect.\n Verify Network.');
			} else if (jqXHR.status == 404) {
				alert('Requested page not found. [404]');
			} else if (jqXHR.status == 500) {
				alert('Internal Server Error [500].');
			} else if (exception === 'parsererror') {
				alert('Requested JSON parse failed.');
			} else if (exception === 'timeout') {
				alert('Time out error.');
			} else if (exception === 'abort') {
				alert('Ajax request aborted.');
			} else {
				alert('Uncaught Error.\n' + jqXHR.responseText);
			}
		}
	});
}

function manageUserModal(){
	$('.ui.manage.user.modal').modal('show');
	$('.ui.checkbox')
		.checkbox()
	;
	loadUser();
}

function togglePasswordView(eye){
	password_field = eye.nextElementSibling;
	password_field.type = password_field.type === "password"? "text" : "password";
	$(eye).toggleClass("active");
}

function loadUser(){
	$.ajax({
		url: "./api/user/read_all.php",
		success: function(result){
			$('[data-id="new"] [name="username"]').val("");
			$('[data-id="new"] [name="password"]').val("");
			$('[data-id="new"] .is_admin.checkbox').checkbox("uncheck");
			if(result['data'] === undefined || result['data'].lenght == 0){
				$('.user.table tbody').html("<tr><td colspan=\"4\">ไม่พบข้อมูลผู้ใช้</td></tr>");
			}else{
				$('.user.table tbody').html("");
				$.each(result['data'], function(index, user) {
					if(user['id'] == 0) return;
					is_mainuser = (user['id'] == 1) ? " disabled" : "";
					is_admin = Number(user['is_admin']) ? " checked" : "";
					$('.user.table tbody').append(
						`<tr data-id="${user['id']}">
							<td>
								<div class="field">
									<div class="ui input">
										<input type="type" name="username" value="${user['username']}">
									</div>
								</div>		
							</td>
							<td>
								<div class="ui icon input">
									<i class="eye link icon" ></i>
									<input type="password" name="password" placeholder="เปลี่ยนรหัสผ่าน">
								</div>
							</td>
							<td class="middle aligned">
								<div class="is_admin ui${is_mainuser} toggle checkbox">
									<input name="is_admin" type="checkbox" tabindex="0" class="hidden"${is_admin}>
								</div>
							</td>
							<td class="middle aligned">
								<div class="middle aligned inline field">
									<div class ="ui fluid buttons">
									<div class="ui teal icon button" onclick="editUserId(${user['id']});"><i class="save outline icon"></i></div>
									<div class="ui${is_mainuser} red icon button" onclick="if(confirm('ยืนยันการลบชื่อผู้ใช้ ?'))deleteUserId(${user['id']});"><i class="trash alternate outline icon"></i></div>
									</div>
								</div>
							</td>
						</tr>`
					);
				});
				$('.ui.checkbox').checkbox();
				$('.eye.link.icon').on('click', function(){togglePasswordView(this)});
			}   
		}
	});		
}

function editUserId(id){
	username = $('[data-id="'+id+'"] [name="username"]').val();
	password = $('[data-id="'+id+'"] [name="password"]').val();
	is_admin = $('[data-id="'+id+'"] .is_admin.checkbox').checkbox("is checked");

	$.ajax({
		url: './api/user/edit_one.php',
		dataType: 'JSON',
		type: 'POST',
		data: { 'id': id,
				'username': username,
				'password': password,
				'is_admin': is_admin
			},
		success: function( data, textStatus, jQxhr ){
			alert(data['message']);
			loadUser();
		},
		error: function(jqXHR, exception) {
			if (jqXHR.status === 0) {
				alert('Not connect.\n Verify Network.');
			} else if (jqXHR.status == 404) {
				alert('Requested page not found. [404]');
			} else if (jqXHR.status == 500) {
				alert('Internal Server Error [500].');
			} else if (exception === 'parsererror') {
				alert('Requested JSON parse failed.');
			} else if (exception === 'timeout') {
				alert('Time out error.');
			} else if (exception === 'abort') {
				alert('Ajax request aborted.');
			} else {
				alert('Uncaught Error.\n' + jqXHR.responseText);
			}
		}
	});
}

function newUser(){
	username = $('[data-id="new"] [name="username"]').val();
	password = $('[data-id="new"] [name="password"]').val();
	is_admin = $('[data-id="new"] .is_admin.checkbox').checkbox("is checked");

	$.ajax({
		url: './api/user/new.php',
		dataType: 'JSON',
		type: 'POST',
		data: { 'username': username,
				'password': password,
				'is_admin': is_admin
			},
		success: function( data, textStatus, jQxhr ){
			console.log(data['message']);
			loadUser();
		},
		error: function(jqXHR, exception) {
			if (jqXHR.status === 0) {
				alert('Not connect.\n Verify Network.');
			} else if (jqXHR.status == 404) {
				alert('Requested page not found. [404]');
			} else if (jqXHR.status == 500) {
				alert('Internal Server Error [500].');
			} else if (exception === 'parsererror') {
				alert('Requested JSON parse failed.');
			} else if (exception === 'timeout') {
				alert('Time out error.');
			} else if (exception === 'abort') {
				alert('Ajax request aborted.');
			} else {
				alert('Uncaught Error.\n' + jqXHR.responseText);
			}
		}
	});
}

function deleteUserId(id){
	$.ajax({
		url: './api/user/delete.php',
		dataType: 'JSON',
		type: 'POST',
		data: { 'id': id},
		success: function( data, textStatus, jQxhr ){
			console.log(data['message']);
			loadUser();
		},
		error: function(jqXHR, exception) {
			if (jqXHR.status === 0) {
				alert('Not connect.\n Verify Network.');
			} else if (jqXHR.status == 404) {
				alert('Requested page not found. [404]');
			} else if (jqXHR.status == 500) {
				alert('Internal Server Error [500].');
			} else if (exception === 'parsererror') {
				alert('Requested JSON parse failed.');
			} else if (exception === 'timeout') {
				alert('Time out error.');
			} else if (exception === 'abort') {
				alert('Ajax request aborted.');
			} else {
				alert('Uncaught Error.\n' + jqXHR.responseText);
			}
		}
	});
}