<?php
class Record{
	private $table_name = "RECORDS";
	private $conn;
	public  $record_time;
	public  $doc_number;
	public  $visit_date;
	public  $occupation;
	public  $n_people;
	public  $address;
	public  $district;
	public  $province;
	public  $country;
	public  $meal_breakfast_price;
	public  $meal_lunch_price;
	public  $meal_dinner_price;
	public  $refreshment_morning_price;
	public  $refreshment_afternoon_price;
	public  $refreshment_evening_price;
	public  $meeting_room_name;
	public  $meeting_room_price;
	public	$single_decker_tram;
	public	$double_decker_tram;
	public	$rooms;
	public  $activities;
	public  $contact;
	public $res;

	public function __construct($db){
		$this->res = $db->res;
		$this->conn = $db->getConnection();
	}

	function readOne(){
		try{
		$query = "SELECT * FROM {$this->table_name} WHERE record_time = '{$this->record_time}'";
			$stmt = $this->conn->prepare($query);
			$stmt->execute();
			$row = $stmt->fetch(PDO::FETCH_ASSOC);
			$this->record_time = $row['record_time'];
			$this->doc_number = $row['doc_number'];
			$this->visit_date = $row['visit_date'];
			$this->occupation = $row['occupation'];
			$this->n_people = $row['n_people'];
			$this->address = $row['address'];
			$this->district = $row['district'];
			$this->province = $row['province'];
			$this->country = $row['country'];
			$this->meal_breakfast_price = $row['meal_breakfast_price'];
			$this->meal_lunch_price = $row['meal_lunch_price'];
			$this->meal_dinner_price = $row['meal_dinner_price'];
			$this->refreshment_morning_price = $row['refreshment_morning_price'];
			$this->refreshment_afternoon_price = $row['refreshment_afternoon_price'];
			$this->refreshment_evening_price = $row['refreshment_evening_price'];
			$this->meeting_room_name = $row['meeting_room_name'];
			$this->meeting_room_price = $row['meeting_room_price'];
			$this->single_decker_tram = $row['single_decker_tram'];
			$this->double_decker_tram = $row['double_decker_tram'];
			$this->rooms = json_decode($row['rooms']);
			$this->activities = json_decode($row['activities']);
			$this->contact = $row['contact'];
		} catch (PDOException $e) {
			$this->res['success'] = false;
			$this->res['error'] = $e->getMessage();
		}
	}

	function readAll(){
		try {
			$query = "SELECT * FROM RECORDS ORDER BY record_time DESC";
			$stmt = $this->conn->prepare($query);
			$stmt->execute();
		} catch (PDOException $e) {
			$this->res['success'] = false;
			$this->res['error'] = $e->getMessage();
		}
		return $stmt;
	}

	function new(){
		try {
			$query = "INSERT INTO {$this->table_name} VALUE(NULL, {$this->doc_number}, {$this->visit_date}, {$this->occupation}, {$this->n_people}, {$this->address}, {$this->district}, {$this->province}, {$this->country}, {$this->meal_breakfast_price}, {$this->meal_lunch_price}, {$this->meal_dinner_price}, {$this->refreshment_morning_price}, {$this->refreshment_afternoon_price}, {$this->refreshment_evening_price}, {$this->single_decker_tram}, {$this->double_decker_tram}, {$this->meeting_room_name}, {$this->meeting_room_price}, {$this->rooms}, {$this->activities}, {$this->contact})";
			$stmt = $this->conn->prepare($query);
			$stmt->execute();
		} catch (PDOException $e) {
			$this->res['success'] = false;
			$this->res['error'] = $e->getMessage();
		}
		return $stmt;
	}

	function search($month, $year, $occupation, $province, $country){
		try{
			$query = "SELECT * FROM {$this->table_name} WHERE occupation LIKE '{$occupation}' AND province LIKE '{$province}' AND month(visit_date) LIKE '{$month}' AND year(visit_date) LIKE '{$year}' AND country LIKE '{$country}' ORDER BY record_time DESC";
			$stmt = $this->conn->prepare($query);
			$stmt->execute();
		} catch (PDOException $e) {
			$this->res['success'] = false;
			$this->res['error'] = $e->getMessage();
		}
		return $stmt;
	}

	function editOne(){
		try {
		$query = "UPDATE {$this->table_name} SET doc_number = {$this->doc_number}, visit_date = {$this->visit_date}, occupation = {$this->occupation}, n_people = {$this->n_people}, address = {$this->address}, district = {$this->district}, country = {$this->country}, meal_breakfast_price = {$this->meal_breakfast_price}, meal_lunch_price = {$this->meal_lunch_price}, meal_dinner_price = {$this->meal_dinner_price}, refreshment_morning_price = {$this->refreshment_morning_price}, refreshment_afternoon_price = {$this->refreshment_afternoon_price}, refreshment_evening_price = {$this->refreshment_evening_price}, meeting_room_name = {$this->meeting_room_name}, meeting_room_price = {$this->meeting_room_price}, single_decker_tram = {$this->single_decker_tram}, double_decker_tram = {$this->double_decker_tram}, rooms = {$this->rooms}, activities = {$this->activities}, contact = {$this->contact} WHERE record_time = {$this->record_time}";
			$stmt = $this->conn->prepare($query);
			$stmt->execute();
		} catch (PDOException $e) {
			$this->res['success'] = false;
			$this->res['error'] = $e->getMessage();
		}
		return $stmt;
	}

	function delete(){
		try{
			$query = "DELETE FROM {$this->table_name} WHERE record_time = '{$this->record_time}'";
			$stmt = $this->conn->prepare($query);
			$stmt->execute();
		} catch (PDOException $e) {
			$this->res['success'] = false;
			$this->res['error'] = $e->getMessage();
		}
		return $stmt;
	}
}
?>