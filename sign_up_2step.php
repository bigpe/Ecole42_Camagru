<?php
require("database.php");
include("check_exists.php");

session_start();

$token = $_SESSION['token'];
$login = $_POST['login'];
$passwd = hash('md5', $_POST['passwd']);
$re_passwd = hash('md5', $_POST['re_passwd']);


$err = 1;

if (!preg_match("/^[0-9a-zA-Z]*$/", $login))
    $err = 12;
if (strlen($login) < 5)
    $err = 11;
if (strlen($_POST['passwd']) < 8)
    $err = 4;
if ($passwd != $re_passwd)
    $err = 3;
if (check_exists('login', 'USERS', $login))
    $err = 7;
if ($err == 1)
{
    $query = "SELECT email FROM USER_TEMP WHERE token='$token'";
    $email = $db->db_read($query);
    $query = "DELETE FROM USER_TEMP WHERE email='$email'";
    $db->db_change($query);
    $query = "INSERT INTO USERS(login, email, password) VALUES ('$login', '$email', '$passwd')";
    $db->db_change($query);
    header("Location: index.php");
}
else
    header("Location: index.php?action=activate&token=$token&error=$err");
?>
