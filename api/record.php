<?php
class Record{
	private $conn;
	private $table_name = "RECORDS";

	public $record_time;
	public $doc_number;
	public $visit_date;
	public $occupation;
	public $n_people;
	public $address;
	public $district;
	public $province;
	public $country;
	public $meal_price;
	public $meal_quantity;
	public $personal_room;
	public $personal_room_quantity;
	public $group_room;
	public $group_room_quantity;
	public $meeting_room;

	public function __construct($db){
		$this->conn = $db;
	}

	function readOne(){
		try{
		$query = "SELECT record_time, doc_number, visit_date, occupation, n_people, address, district, province, country, meal_price, meal_quantity, personal_room, personal_room_quantity, group_room, group_room_quantity, meeting_room FROM {$this->table_name} WHERE record_time = '{$this->record_time}'";
			$stmt = $this->conn->prepare($query);
			$stmt->execute();
			$row = $stmt->fetch(PDO::FETCH_ASSOC);
			$this->doc_number = $row['doc_number'];
			$this->visit_date = $row['visit_date'];
			$this->occupation = $row['occupation'];
			$this->n_people = $row['n_people'];
			$this->address = $row['address'];
			$this->district = $row['district'];
			$this->province = $row['province'];
			$this->country = $row['country'];
			$this->meal_price = $row['meal_price'];
			$this->meal_quantity = $row['meal_quantity'];
			$this->personal_room = $row['personal_room'];
			$this->personal_room_quantity = $row['personal_room_quantity'];
			$this->group_room = $row['group_room'];
			$this->group_room_quantity = $row['group_room_quantity'];
			$this->meeting_room = $row['meeting_room'];
		} catch (PDOException $e) {
			echo "Error: " . $e->getMessage();
		}
	}

	function readAll(){
		try {
			$query = "SELECT record_time, doc_number, visit_date, occupation, n_people, address, district, province, country, meal_price, meal_quantity, personal_room, personal_room_quantity, group_room, group_room_quantity, meeting_room FROM RECORDS";
			$stmt = $this->conn->prepare($query);
			$stmt->execute();
			return $stmt;
		} catch (PDOException $e) {
			echo "Error: " . $e->getMessage();
		}
	}

	function new(){
		try {
			$query = "INSERT INTO {$this->table_name} VALUE(NULL, '{$this->doc_number}', '{$this->visit_date}', '{$this->occupation}', '{$this->n_people}', '{$this->address}', '{$this->district}', '{$this->province}', '{$this->country}', '{$this->meal_price}', '{$this->meal_quantity}', '{$this->personal_room}', '{$this->personal_room_quantity}', '{$this->group_room}', '{$this->group_room_quantity}', '{$this->meeting_room}')";
			$stmt = $this->conn->prepare($query);
			$stmt->execute();
			return $stmt;
		} catch (PDOException $e) {
			echo "Error: " . $e->getMessage();
		}
	}

	function search($month, $year, $occupation, $province, $country){
		try{
			$query = "SELECT record_time, doc_number, visit_date, occupation, n_people, address, district, province, country, meal_price, meal_quantity, personal_room, personal_room_quantity, group_room, group_room_quantity, meeting_room FROM {$this->table_name} WHERE occupation LIKE '{$occupation}' AND province LIKE '{$province}' AND month(visit_date) LIKE '{$month}' AND year(visit_date) LIKE '{$year}' AND country LIKE '{$country}'";
			$stmt = $this->conn->prepare($query);
			$stmt->execute();
			return $stmt;
		} catch (PDOException $e) {
			echo "Error: " . $e->getMessage();
		}
	}

	function editOne(){
		try {
		$query = "UPDATE {$this->table_name} SET record_time = NOW(), doc_number = '{$this->doc_number}', visit_date = '{$this->visit_date}', occupation = '{$this->occupation}', n_people = '{$this->n_people}', address = '{$this->address}', district = '{$this->district}', province = '{$this->province}', country = '{$this->country}', meal_price = '{$this->meal_price}', meal_quantity = '{$this->meal_quantity}', personal_room = '{$this->personal_room}', personal_room_quantity = '{$this->personal_room_quantity}', group_room = '{$this->group_room}', group_room_quantity = '{$this->group_room_quantity}', meeting_room = '{$this->meeting_room}' WHERE record_time = '{$this->record_time}'";
			$stmt = $this->conn->prepare($query);
			$stmt->execute();
			echo $query;
		} catch (PDOException $e) {
			echo "Error: " . $e->getMessage();
		}
		return $stmt;
	}

	function delete(){
		try{
			$query = "DELETE FROM {$this->table_name} WHERE record_time = '{$this->record_time}'";
			$stmt = $this->conn->prepare($query);
			$stmt->execute();
			return $stmt;
		} catch (PDOException $e) {
			echo $sql . "<br>" . $e->getMessage();
		}
	}
}
?>