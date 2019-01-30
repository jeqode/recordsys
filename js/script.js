months = ['มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษพาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'];
$(function(){
	$('.ui.dropdown')
  		.dropdown()
	;
	$('.record .province.ui.dropdown')
  		.dropdown({
			allowAdditions: true
		  })
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

function updateRoomUnit(tag){
	parent = tag.parentElement.parentElement.parentElement.parentElement;
	$(parent.querySelector('[name="room_quantity"] + .label')).html($(parent.querySelector('[name="room_price_tag"]')).val().split('/')[1]);
}

function addRoom(modal, name="",price="", price_tag="บาท/คน", quantity=""){
	$('.disabled.field input, .disabled.field .button').attr("tabindex","0");
	$('.disabled.field').removeClass("disabled ");
	disabled = name.length ? "disabled" : "";
	$.ajax({
		type: 'GET',
		url: './api/config/variables.php?var=rooms',
		dataType: 'JSON',
		success: function(data){
			room_html = `
				<div class="ui dimmable fields room">
					<div class="ui inverted dimmer">
						<div class="content">
							<div class="ui compact basic button" onclick="addRoom('${modal}');">เพิ่มห้องพัก</div>
						</div>
					</div>
					<div class="${disabled} five wide field">
						<div class="occupation ui fluid floating labeled icon search dropdown button">
							<i class="home alternate icon"></i>
							<input type="hidden" name="room_name" value="${name}">
							<span class="text">ห้องพัก</span>
							<div class="menu">
								<div class="item" data-value="">เลือกห้องพัก</div>`;
			rooms = data;
			for (var i = 0; i < rooms.length; i++){
				room_html += `<div class="item">${rooms[i]}</div>\n`;
			}
			room_html += `
						</div>
					</div>
				</div>
				<div class="${disabled} five wide field">
					<div class="ui fluid right labeled input">
						<label for="room_price" class="ui label">ราคา</label>
						<input type="text" name="room_price" placeholder="ราคา" value="${price}">
						<div class="ui dropdown label">
							<div class="text">บาท/คน</div>
							<i class="dropdown icon"></i>
							<input type="hidden" name="room_price_tag" value="${price_tag}" onchange="updateRoomUnit(this);">
							<div class="menu">
								<div class="item">บาท/คน</div>
								<div class="item">บาท/หลัง</div>
								<div class="item">บาท/เต้นท์</div>
							</div>
						</div>
					</div>
				</div>
				<div class="${disabled} four wide field">
					<div class="ui fluid right labeled input">
						<label for="room_quantity" class="ui label">จำนวน</label>
						<input type="text" name="room_quantity" placeholder="จำนวน" value="${quantity}">
						<div class="ui basic label">คน</div>
					</div>
				</div>
				<div class="${disabled} two wide field">
					<div class="ui fluid basic button" onclick="removeRoom(this);">ลบ</div>
				</div>
				</div>`;
			$('.'+modal+'.record .rooms.list').append(room_html);
			$('.dropdown').dropdown();
			$('.disabled.field input, .disabled.field .button').attr("tabindex","-1");
			$('.dimmable.room .dimmer').dimmer('hide');
			$('.dimmable.room:last-child .dimmer').dimmer({closable:false}).dimmer('show');
		}
	})
}

function removeRoom(button){
	room = button.parentElement.parentElement;
	room.remove();
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
			'province': filter.province,
			'country': filter.country
		},
		success: function(data, textStatus, jQxhr){
			if(data['data'] === undefined || data['data'].length == 0){
				$('#records').html("<tr><td colspan=\"15\" class=\"ui center aligned item\">ไม่พบข้อมูล</td></tr>");
			}else{
				$('#records').html("");
				$.each(data['data'], function(index, record) {
					activities = record['activities'] != null && record['activities'].length > 0 ? record['activities'].join(', ') : "-";
					rooms = record['rooms'];
					rowspan_rooms = "";
					room = Object();
					if (rooms != null && rooms.length > 0){
						room_n = rooms.length;
						room = rooms.shift();
						rowspan_rooms = room_n > 1 ? ' rowspan="' + room_n + ' " ' : ""; 
					}else{
						room['name'] = "-";
						room['price'] = "-";
						room['quantity'] = "-";
					}
					single_decker_tram = $.isNumeric(record['single_decker_tram']) ? record['single_decker_tram'] : "-";
					double_decker_tram = $.isNumeric(record['double_decker_tram']) ? record['double_decker_tram'] : "-";
					meeting_room = record['meeting_room_name'] != null ? record['meeting_room_name'] : "-";
					meeting_room += record['meeting_room_price'] != null ? " ราคา " + record['meeting_room_price'] + " บาท" : "";
					contact = record['contact'] != null ? record['contact'] : "-";
					breakfast = record['meal_breakfast_price'] ? record['meal_breakfast_price'] : "-";
					lunch = record['meal_lunch_price'] ? record['meal_lunch_price'] : "-";
					dinner = record['meal_dinner_price'] ? record['meal_dinner_price'] : "-";
					morning = record['refreshment_morning_price'] ? record['refreshment_morning_price'] : "-";
					afternoon = record['refreshment_afternoon_price'] ? record['refreshment_afternoon_price'] : "-";
					evening = record['refreshment_evening_price'] ? record['refreshment_evening_price'] : "-";
					foods = `
					<div class='content'>
						<table class='ui center aligned very basic celled table'>
							<thead>
								<tr>
									<th colspan='3'>อาหารหลัก</th>
									<th colspan='3'>อาหารว่าง</th>
								</tr>
								<tr>
									<th>เช้า (บาท)</th>
									<th>กลางวัน (บาท)</th>
									<th>เย็น (บาท)</th>
									<th>เช้า (บาท)</th>
									<th>บ่าย (บาท)</th>
									<th>ดึก (บาท)</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>${breakfast}</td>
									<td>${lunch}</td>
									<td>${dinner}</td>
									<td>${morning}</td>
									<td>${afternoon}</td>
									<td>${evening}</td>
								</tr>
							</tbody>
						</table>
					</div>
					`;
					$('#records').append(`
					<tr>
						<td ${rowspan_rooms}>${record['doc_number']}</td>
						<td ${rowspan_rooms}>${record['visit_date']}</td>
						<td ${rowspan_rooms}>${record['occupation']}</td>
						<td ${rowspan_rooms}>${record['n_people']}</td>
						<td ${rowspan_rooms}>${record['address']} ${record['district']} ${record['province']} ประเทศ${record['country']}</td>
						<td ${rowspan_rooms}><a class="got-popup ui label" data-html="${foods}">รายละเอียด</a></td>
						<td ${rowspan_rooms}>${single_decker_tram}</td>
						<td ${rowspan_rooms}>${double_decker_tram}</td>
						<td ${rowspan_rooms}>${meeting_room}</td>
						<td>${room['name']}</td>
						<td>${room['price']}</td>
						<td>${room['quantity']}</td>
						<td ${rowspan_rooms}>${activities}</td>
						<td ${rowspan_rooms}>${contact}</td>
						<td ${rowspan_rooms}class="middle aligned">
							<div class ="ui buttons">
								<div class="ui icon button" onclick="readRecordByRecordTime('${record['record_time']}');"><i class="edit outline icon"></i></div>
								<div class="ui red icon button" onclick="if(confirm('ยืนยันการลบข้อมูล ?'))deleteRecordByRecordTime('${record['record_time']}');"><i class="trash alternate outline icon"></i></div>
							</div>
						</td>
					</tr>
					`);

					if (rooms != null) {
						while (rooms.length > 0){
							room = rooms.shift();
							$('#records').append(`
							<tr>
								<td>${room['name']}</td>
								<td>${room['price']}</td>
								<td>${room['quantity']}</td>
							</tr>
							`);
						}
					}
				});
			}  
			$('.got-popup').popup(); 
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
	filter.country = $('.filter [name="country"]').val() != "" ? $('.filter [name="country"]').val() : "%" ;
	loadReports(filter);
	$('a.export.excel.button').attr("href","api/record/export.php?month="+filter.month+"&year="+filter.year+"&occupation="+filter.occupation+"&province="+filter.province+"&country="+filter.country);
	$('a.print.button').attr("href","api/record/print.php?month="+filter.month+"&year="+filter.year+"&occupation="+filter.occupation+"&province="+filter.province+"&country="+filter.country);
}

function resetFilter(){
	$('.filter .dropdown').dropdown("restore defaults");
	applyFilter();
}

function addRecordModal(){
	$('.ui.add.record.modal').modal('show');
	$('.dropdown').dropdown({clearable: true, allowAdditions: true});
	today = new Date();
	$('.add.record [name="visit_date"]').val(formatDate(today));
	$('.add.record [name="doc_number"]').val("");
	$('.add.record [name="visit_date"]').val(formatDate(today));
	$('.add.record .occupation.dropdown').dropdown("restore defaults");
	$('.add.record [name="address"]').val("");
	$('.add.record [name="district"]').val("");
	$('.add.record .province.dropdown').dropdown("clear");
	$('.add.record .province.dropdown').dropdown("set text", "จังหวัด");
	$('.add.record .country.dropdown').dropdown("set selected", "ไทย");
	$('.add.record [name="n_people"]').val("");
	$('.add.record [name="meal_breakfast_price"]').val("");
	$('.add.record [name="meal_lunch_price"]').val("");
	$('.add.record [name="meal_dinner_price"]').val("");
	$('.add.record [name="refreshment_morning_price"]').val("");
	$('.add.record [name="refreshment_afternoon_price"]').val("");
	$('.add.record [name="refreshment_evening_price"]').val("");
	$('.add.record [name="single_decker_tram"]').val("");
	$('.add.record [name="double_decker_tram"]').val("");
	rooms = $('.add.record .rooms.list .room');
	for (var i = 0; i < rooms.length; i++){
		if(i < 2){
			$(rooms[i].querySelector('.room_name.dropdown')).dropdown("clear");
			$(rooms[i].querySelector('.room_name.dropdown')).dropdown("set text", "ที่พัก");
			$(rooms[i].querySelector('[name="room_price"]')).val("");
			$(rooms[i].querySelector('[name="room_quantity"]')).val("");
		}else{
			rooms[i].remove();
		}
	}
	$('.add.record [name="meeting_room_name"]').val("");
	$('.add.record [name="meeting_room_price"]').val("");
	$('.add.record .activities.dropdown').dropdown("clear");
	$('.add.record .activities.dropdown').dropdown("set text", "กิจกรรม");
	$('.add.record [name="contact"]').val("");
	$('.dimmable.room .dimmer').dimmer({closable: false}).dimmer('show');
	$('.disabled.field input, .disabled.field .button').attr("tabindex","-1");
}

function addRecord(){
	doc_number = $('.add.record [name="doc_number"]').val();
	visit_date = $('.add.record [name="visit_date"]').val();
	occupation = $('.add.record [name="occupation"]').val();
	address = $('.add.record [name="address"]').val();
	district = $('.add.record [name="district"]').val();
	province = $('.add.record [name="province"]').val();
	country = $('.add.record [name="country"]').val();
	n_people = $('.add.record [name="n_people"]').val();
	meal_breakfast_price = $('.add.record [name="meal_breakfast_price"]').val();
	meal_lunch_price = $('.add.record [name="meal_lunch_price"]').val();
	meal_dinner_price = $('.add.record [name="meal_dinner_price"]').val();
	refreshment_morning_price = $('.add.record [name="refreshment_morning_price"]').val();
	refreshment_afternoon_price = $('.add.record [name="refreshment_afternoon_price"]').val();
	refreshment_evening_price = $('.add.record [name="refreshment_evening_price"]').val();
	single_decker_tram = $('.add.record [name="single_decker_tram"]').val();
	double_decker_tram = $('.add.record [name="double_decker_tram"]').val();
	meeting_room_name = $('.add.record [name="meeting_room_name"]').val();
	meeting_room_price = $('.add.record [name="meeting_room_price"]').val();
	activities = $('.add.record [name="activities"]').val().split(',');
	contact = $('.add.record [name="contact"]').val();
	activities = activities.length > 0 && activities[0].replace(/\s/g, '').length ? JSON.stringify(activities) : null;
	rooms_array = $('.add.record .rooms.list .room');
	rooms = Array();
	for (i = 0; i < rooms_array.length-1; i++){
		room = Object();
		name = $(rooms_array[i].querySelector('.add.record [name="room_name"]')).val();
		price = $(rooms_array[i].querySelector('.add.record [name="room_price"]')).val();
		quantity = $(rooms_array[i].querySelector('.add.record [name="room_quantity"]')).val();
		if (name || price || quantity){
			room.name = name;
			room.price =   price + " " + $(rooms_array[i].querySelector('.add.record [name="room_price_tag"]')).val();
			room.quantity =  quantity;
			rooms.push(room);
		}
	}
	rooms = rooms.length > 0 ? JSON.stringify(rooms) : null;
	$.ajax({
		url: './api/record/new.php',
		dataType: 'text',
		type: 'POST',
		data: { 
			'doc_number': doc_number,
			'visit_date': visit_date,
			'occupation': occupation,
			'n_people': n_people,
			'address': address,
			'district': district,
			'province': province,
			'country': country,
			'meal_breakfast_price': meal_breakfast_price,
			'meal_lunch_price': meal_lunch_price,
			'meal_dinner_price': meal_dinner_price,
			'refreshment_morning_price': refreshment_morning_price,
			'refreshment_afternoon_price': refreshment_afternoon_price,
			'refreshment_evening_price': refreshment_evening_price,
			'meeting_room_name': meeting_room_name,
			'meeting_room_price': meeting_room_price,
			'single_decker_tram': single_decker_tram,
			'double_decker_tram': double_decker_tram,
			'rooms': rooms,
			'activities': activities,
			'contact': contact
		},
		success: function( data, textStatus, jQxhr ){
			console.log(data);
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
		type: "GET",
		data: {
			'record_time': time
		},
		success: function(data, textStatus, jQxhr){
			$('.edit.record').attr("data-json", JSON.stringify(data));
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
	$('.ui.edit.record.modal').modal({onShow: function(){
		data = JSON.parse(document.querySelector('.edit.record.modal').dataset.json);
		$('.edit.record [name="doc_number"]').val(data['doc_number']);
		$('.edit.record [name="visit_date"]').val(data['visit_date']);
		$('.edit.record .occupation.dropdown').dropdown("set selected", data['occupation']);
		$('.edit.record [name="address"]').val(data['address']);
		$('.edit.record [name="district"]').val(data['district']);
		$('.edit.record .province.dropdown').dropdown("set selected", data['province']);
		$('.edit.record .country.dropdown').dropdown("set selected", data['country']);
		$('.edit.record [name="n_people"]').val(data['n_people']);
		$('.edit.record [name="meal_breakfast_price"]').val(data['meal_breakfast_price']);
		$('.edit.record [name="meal_lunch_price"]').val(data['meal_lunch_price']);
		$('.edit.record [name="meal_dinner_price"]').val(data['meal_dinner_price']);
		$('.edit.record [name="refreshment_morning_price"]').val(data['refreshment_morning_price']);
		$('.edit.record [name="refreshment_afternoon_price"]').val(data['refreshment_afternoon_price']);
		$('.edit.record [name="refreshment_evening_price"]').val(data['refreshment_evening_price']);
		$('.edit.record [name="single_decker_tram"]').val(data['single_decker_tram']);
		$('.edit.record [name="double_decker_tram"]').val(data['double_decker_tram']);
		$('.edit.record .meeting_room_name.dropdown').dropdown("set selected", data['meeting_room_name']?data['meeting_room_name']:"");
		$('.edit.record [name="meeting_room_price"]').val(data['meeting_room_price']);
		$('.edit.record .activities.dropdown').dropdown("set selected", data['activities']);
		$('.edit.record [name="contact"]').val(data['contact']);
		$('.edit.record .teal.button').attr("onclick", "editRecord('"+data['record_time']+"');");
		rooms = data['rooms'];
		rooms_array = $('.edit.record .rooms.list .room');
		for (i = 0; i < rooms_array.length; i++){
			if (i == 0){
				$(rooms_array[i].querySelector('.room_name.dropdown')).dropdown("set selected", "");
				$(rooms_array[i].querySelector('[name="room_price"]')).val("");
				$(rooms_array[i].querySelector('.room_price_tag.dropdown')).dropdown("set selected", "บาท/คน");
				$(rooms_array[i].querySelector('[name="room_quantity"]')).val("");
			}else{
				rooms_array[i].remove();
			}
		}
		if(rooms != null){
			for (i = 0; i < rooms.length; i++){
				price = rooms[i]['price'].split(' ');
				name = rooms[i]['name'];
				quantity = rooms[i]['quantity'];
				if(i > 0){
					addRoom('edit', name, price[0], price[1], quantity);
				}else{
					$(rooms_array[0].querySelector('.room_name.dropdown')).dropdown("set selected", name);
					$(rooms_array[0].querySelector('[name="room_price"]')).val(price[0]);
					$(rooms_array[0].querySelector('.room_price_tag.dropdown')).dropdown("set selected", price[1]);
					$(rooms_array[0].querySelector('[name="room_quantity"]')).val(quantity);
				}
			}
			addRoom('edit');
		}
	}});
	$('.ui.edit.record.modal').modal('show');
}



function editRecord(time){
	doc_number = $('.edit.record [name="doc_number"]').val();
	visit_date = $('.edit.record [name="visit_date"]').val();
	occupation = $('.edit.record [name="occupation"]').val();
	address = $('.edit.record [name="address"]').val();
	district = $('.edit.record [name="district"]').val();
	province = $('.edit.record [name="province"]').val();
	country = $('.edit.record [name="country"]').val();
	n_people = $('.edit.record [name="n_people"]').val();
	meal_breakfast_price = $('.edit.record [name="meal_breakfast_price"]').val();
	meal_lunch_price = $('.edit.record [name="meal_lunch_price"]').val();
	meal_dinner_price = $('.edit.record [name="meal_dinner_price"]').val();
	refreshment_morning_price = $('.edit.record [name="refreshment_morning_price"]').val();
	refreshment_afternoon_price = $('.edit.record [name="refreshment_afternoon_price"]').val();
	refreshment_evening_price = $('.edit.record [name="refreshment_evening_price"]').val();
	single_decker_tram = $('.edit.record [name="single_decker_tram"]').val();
	double_decker_tram = $('.edit.record [name="double_decker_tram"]').val();
	meeting_room_name = $('.edit.record [name="meeting_room_name"]').val();
	meeting_room_price = $('.edit.record [name="meeting_room_price"]').val();
	activities = $('.edit.record [name="activities"]').val().split(',');
	contact = $('.edit.record [name="contact"]').val();
	activities = activities.length > 0 && activities[0].replace(/\s/g, '').length ? JSON.stringify(activities) : null;
	rooms_array = $('.edit.record .rooms.list .room');
	rooms = Array();
	for (i = 0; i < rooms_array.length-1; i++){
		room = Object();
		name = $(rooms_array[i].querySelector('.edit.record [name="room_name"]')).val();
		price = $(rooms_array[i].querySelector('.edit.record [name="room_price"]')).val();
		quantity = $(rooms_array[i].querySelector('.edit.record [name="room_quantity"]')).val();
		if (name || price || quantity){
			room.name = name;
			room.price =   price + " " + $(rooms_array[i].querySelector('.edit.record [name="room_price_tag"]')).val();
			room.quantity =  quantity;
			rooms.push(room);
		}
	}
	rooms = rooms.length > 0 ? JSON.stringify(rooms) : null;
	$.ajax({
		url: './api/record/edit_one.php',
		dataType: 'text',
		type: 'POST',
		data: { 
			'record_time': time,
			'doc_number': doc_number,
			'visit_date': visit_date,
			'occupation': occupation,
			'n_people': n_people,
			'address': address,
			'district': district,
			'province': province,
			'country': country,
			'meal_breakfast_price': meal_breakfast_price,
			'meal_lunch_price': meal_lunch_price,
			'meal_dinner_price': meal_dinner_price,
			'refreshment_morning_price': refreshment_morning_price,
			'refreshment_afternoon_price': refreshment_afternoon_price,
			'refreshment_evening_price': refreshment_evening_price,
			'meeting_room_name': meeting_room_name,
			'meeting_room_price': meeting_room_price,
			'single_decker_tram': single_decker_tram,
			'double_decker_tram': double_decker_tram,
			'rooms': rooms,
			'activities': activities,
			'contact': contact
		},
		success: function( data, textStatus, jQxhr ){
			console.log(data);
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
			if(result['data'] === undefined || result['data'].length == 0){
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