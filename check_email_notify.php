<?php
require_once "database.php";

session_start();

$login = $_SESSION['User_session'];
$email_notify = $db->db_read("SELECT email_notify FROM USERS WHERE login='$login' or email='$login'");
print ($email_notify);

?>