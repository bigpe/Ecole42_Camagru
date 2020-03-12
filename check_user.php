<?php
require_once "database.php";

header("Content-Type: application/json");
session_start();
$login = $_SESSION['User_session'];
$username = array("username" => $db->db_read("SELECT login FROM USERS WHERE login='$login' or email='$login'"));

print(json_encode($username));
?>