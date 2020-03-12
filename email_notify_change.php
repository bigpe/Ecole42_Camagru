<?php
require_once "database.php";
session_start();
$login = $_SESSION['User_session'];
$email_notify_status = (int)$_POST['email_notify_status'];

$db->db_change("UPDATE USERS SET email_notify='$email_notify_status' WHERE login='$login' or email='$login'");

?>