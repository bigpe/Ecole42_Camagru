<?php
require_once "check_exists.php";
require_once "database.php";

session_start();

$old_login = $_SESSION['User_session'];
$login = $_POST['login'];
$err = 1;
if (check_exists('login', 'USERS', $login))
    $err = 7;
if (!preg_match("/^[0-9a-zA-Z]*$/", $login))
    $err = 12;
if (strlen($login) < 5)
    $err = 11;
if ($err == 1)
{
    $_SESSION['User_session'] = $login;
    $query = "UPDATE USERS SET login='$login' WHERE login='$old_login' OR email='$old_login'";
    $db->db_change($query);
    header("Location: settings.php");
}
else
    header("Location: index.php?action=change_login&error=$err");
?>