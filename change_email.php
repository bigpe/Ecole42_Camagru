<?php
require_once "database.php";
require_once "check_exists.php";
require_once "mail.php";

session_start();
$login = $_SESSION['User_session'];
$email = $_POST['email'];
$server = $_SERVER['HTTP_HOST'];
$times = time();

$err = 1;
if (!preg_match("/[0-9a-z]+@[a-z]+\.[a-z]/", $email))
    $err = 2;
if (check_exists('email', 'USERS', "$email"))
    $err = 8;
if ($err == 1)
{
    $token = hash('md5',$email . $times);
    $msg = "<p>To continue change email, please click link.</p><br>
    <a href='http://$server?action=change_email_2step&token=$token'>Email Change Confirm</a>
    <p>Camagru project</p>";

    $query = "SELECT email FROM USERS
    JOIN EMAIL_CHANGE ON USERS.user_id = EMAIL_CHANGE.user_id 
    WHERE email='$login' or login='$login'";

    if(!$db->db_check($query))
        $query = "INSERT INTO EMAIL_CHANGE (new_email, token, user_id)
                SELECT '$email', '$token', user_id
                FROM USERS WHERE login='$login' OR email='$login'";
    else
        $query = "UPDATE EMAIL_CHANGE as dest,
                (SELECT user_id FROM USERS WHERE login='$login' OR email='$login') as src
                SET dest.new_email='$email', dest.token='$token' WHERE dest.user_id=src.user_id";
    $db->db_change($query);
    if(!send_mail($email, $msg))
        $err = 6;
    header("Location: index.php?action=success");
}
else
    header("Location: index.php?action=change_email&error=$err");
?>