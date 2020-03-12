<?php
require_once 'database.php';

session_start();

$token = $_SESSION['token'];
$passwd = hash('md5', $_POST['passwd']);
$re_passwd = hash('md5', $_POST['re_passwd']);

$err = 1;

if (strlen($_POST['passwd']) < 8)
    $err = 4;
elseif ($passwd != $re_passwd)
    $err = 3;
if ($err == 1)
{
    $query = "UPDATE USERS as dest,
    (SELECT email FROM USER_RESTORE WHERE token='$token') as src
    SET dest.password='$passwd' WHERE dest.email=src.email";
    $db->db_change($query);
    $query = "DELETE FROM USER_RESTORE WHERE token='$token'";
    $db->db_change($query);
    $_SESSION = array();
    header('Location: index.php');
}
else
    header("Location: index.php?action=restore&token=$token&error=$err");
?>