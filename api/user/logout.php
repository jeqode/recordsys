<?php 
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("AccessControl-Allow-Credentials: true");
header("Content-Type: application/json");
session_start();

$_SESSION['id'] = "";
$_SESSION['user'] = "";
$_SESSION['username'] = "";
$_SESSION['firstname'] = "";
$_SESSION['lastname'] = "";
$_SESSION['tel'] = "";
$_SESSION['is_admin'] = "";

?>