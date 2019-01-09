$(function(){
	$('.ui.dropdown')
  		.dropdown()
	;
	$('.ui.checkbox')
		.checkbox()
	;
	loadReports();

	$('.ui.modal')
		.modal({
			blurring: true,
			closable: false,
			allowMultiple: true
		});
});
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

function loadReports(){
	$.ajax({
		url: "./api/record/read_all.php",
		success: function(result){
			if(result['data'] === undefined || result['data'].lenght == 0){
				$('#records').html("<tr><td class=\"ui center aligned item\">ไม่พบข้อมูล</td></tr>");
			}else{
				$('#records').html("");
				$.each(result['data'], function(index, record) {
					$('#records').append(`
					<tr>
						<td>${record['doc_number']}</td>
						<td>${record['visit_date']}</td>
						<td>${record['occupation']}</td>
						<td>${record['n_people']}</td>
						<td>${record['address']} อ.${record['district']} จ.${record['province']}</td>
						<td>${record['meal_price']}</td>
						<td>${record['meal_quantity']}</td>
						<td>${record['personal_room']}</td>
						<td>${record['personal_room_quantity']}</td>
						<td>${record['group_room']}</td>
						<td>${record['group_room_quantity']}</td>
						<td>${record['meeting_room']}</td>
					</tr>
					`);
					
				});
			}   
		}
	});	
}

function addRecordModal(){
	$('.ui.add.record.modal').modal('show');
	$('#datetimepicker').calendar({
		type: 'date',
		text: {
			days: ['อา', 'จ', 'อ', 'พ', 'พฤ', 'ศ', 'ส'],
			months: ['มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษพาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'],
			monthsShort: ['ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย.', 'พ.ค.', 'มิ.ย.', 'ก.ค..', 'ส.ค.', 'ก.ย.', 'ต.ค.', 'พ.ย.', 'ธ.ค.'],
			today: 'วันนี้',
			now: 'ตอนนี้',
			am: 'AM',
			pm: 'PM'
		  },
		formatter: {

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

function deleteUserId(id){
	$.ajax({
		url: './api/user/delete.php',
		dataType: 'JSON',
		type: 'POST',
		data: { 'id': id},
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