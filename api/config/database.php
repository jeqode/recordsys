<?php
class Database{
 
    private $host = "localhost";
    private $db_name = "recordsys";
    private $username = "root";
    private $password = "daffodil";
    public $conn;
    public $res = array();
 
    public function getConnection(){
 
        $this->conn = null;
 
        try{
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->exec("set names utf8mb4");
        }catch(PDOException $exception){
            $res['error'] = $exception->getMessage();
        }
 
        return $this->conn;
    }
}
?>