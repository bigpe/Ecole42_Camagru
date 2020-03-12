<?php
include "check_exists.php";

session_start();

$login = $_POST['login'];
$passwd = hash('md5', $_POST['passwd']);

$entry = [$login, $passwd];
$columns = ['login', 'password'];
$columns_reserv = ['email', 'password'];
$table = 'USERS';

if(check_exists($columns, $table, $entry) or check_exists($columns_reserv, $table, $entry))
{
    $_SESSION['User_session'] = $login;
    header("Location: index.php");
}
else
    header("Location: index.php?error=9");
?>