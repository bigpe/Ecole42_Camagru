<?php
require "check_exists.php";
require "mail.php";
require_once "database.php";

session_start();

$email = $_POST['email'];
$err = 1;

$times = time();

if (!preg_match("/[0-9a-z]+@[a-z]+\.[a-z]/", $email))
    $err = 2;
elseif (!check_exists('email', 'USERS', $email))
    $err = 10;
if ($err == 1)
{
    $token = hash('md5', "$times$email");
    $token_url = 'http://' . $_SERVER['HTTP_HOST'] . "/?action=restore&token=$token";

    if (!check_exists('email', 'USER_RESTORE', "$email"))
        $query = "INSERT INTO USER_RESTORE(email, user_id, token) 
                    SELECT '$email', user_id, '$token' 
                    FROM USERS WHERE email='$email'";
    else
        $query = "UPDATE USER_RESTORE SET token='$token' WHERE email='$email'";
    if (!send_mail($email, "Your restore link $token_url"))
        $err = 6;
    else
    {
        $db->db_change($query);
        $_SESSION['token'] = $token;
        header('Location: index.php?action=success');
    }
}
else
    header("Location: index.php?action=restore_password&error=$err");
?>