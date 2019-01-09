<?php
class User{
	private $conn;
	private $table_name = "USERS";

	public $id;
	public $username;
	public $auth_hash;
    public $is_admin;

    public function __construct($db){
        $this->conn = $db;
    }

    function readAll(){
    	try {
    		$query = "SELECT id, username, auth_hash, is_admin FROM {$this->table_name}";
    		$stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt;
    	} catch (PDOException $e) {
    		echo "Error: " . $e->getMessage();
    	}
    }

    function readOne(){
    	try {
			$query = "SELECT id, username, auth_hash, is_admin FROM {$this->table_name} WHERE username = '{$this->username}'";
    		$stmt = $this->conn->prepare($query);
            $stmt->execute();
			$row = $stmt->fetch(PDO::FETCH_ASSOC);
			$this->id = $row['id'];
            $this->username = $row['username'];
            $this->auth_hash = $row['auth_hash'];
            $this->is_admin = $row['is_admin'];
    	} catch (PDOException $e) {
    		echo "Error: " . $e->getMessage();
    	}
	}
	
	function new(){
		$this->auth_hash = password_hash($this->auth_hash, PASSWORD_ARGON2I);
		try {
    		$query = "INSERT INTO {$this->table_name} VALUE(NULL, '{$this->username}', '{$this->auth_hash}', {$this->is_admin})";
    		$stmt = $this->conn->prepare($query);
			$stmt->execute();
			return $stmt;
    	} catch (PDOException $e) {
    		echo "Error: " . $e->getMessage();
    	}
	}

	function editOne(){
		try{
			if ($this->auth_hash === "") {
				$query = "UPDATE {$this->table_name} SET username='{$this->username}', is_admin={$this->is_admin} WHERE id={$this->id}";
			} else {
				$this->auth_hash = password_hash($this->auth_hash, PASSWORD_ARGON2I);
				$query = "UPDATE {$this->table_name} SET username='{$this->username}', auth_hash='{$this->auth_hash}', is_admin={$this->is_admin} WHERE id={$this->id}";
			}
			$stmt = $this->conn->prepare($query);
			$stmt->execute();
			return $stmt;
		} catch (PDOException $e) {
			echo $sql . "<br>" . $e->getMessage();
		}
	}
}
?>