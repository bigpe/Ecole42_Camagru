<?php
require_once "database.php";

session_start();
$err = 1;
$login = $_SESSION['User_session'];
$old_passwd = hash('md5', $_POST['old_passwd']);
$passwd = hash('md5', $_POST['passwd']);
$re_passwd = hash('md5', $_POST['re_passwd']);

$query = "SELECT login FROM USERS WHERE password='$old_passwd' AND login='$login' OR email='$login'";
if(!$db->db_check($query))
    $err = 13;
elseif (strlen($_POST['passwd']) < 8)
    $err = 4;
elseif ($passwd != $re_passwd)
    $err = 3;
if($err == 1)
{
    $query = "UPDATE USERS SET password='$passwd' WHERE email='$login' or login='$login'";
    $db->db_change($query);
    header("Location: settings.php");
}
else
    header("Location: index.php?action=change_password&error=$err");
?>