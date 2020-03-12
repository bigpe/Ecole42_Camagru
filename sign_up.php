<?php
require("database.php");
include("mail.php");
include("check_exists.php");


$email = $_POST['email'];

$err = 1;

if (!preg_match("/[0-9a-z]+@[a-z]+\.[a-z]/", $email))
    $err = 2;
if (check_exists('email', 'USERS', "$email"))
    $err = 8;
if ($err == 1)
{
    $token = hash('md5',"$email" . time());
    $server = $_SERVER['HTTP_HOST'];
    $msg = "<p>To continue registration, click link.</p><br>
<a href='http://$server?action=activate&token=$token'>Registration Confirm</a>
<p>Camagru project</p>";


    if(send_mail($email, $msg))
    {
        if(check_exists('email', 'USER_TEMP', $email))
            $query = "UPDATE USER_TEMP SET token='$token' WHERE email='$email'";
        else
            $query = "INSERT INTO USER_TEMP (email, token) value ('$email', '$token')";
        $db->db_change($query);
        header("Location: index.php?action=success"); #Mail send successful
    }
    else
        header("Location: index.php?error=6"); #Mail not send, show error
}
else
    header("Location: index.php?action=sign_up&error=$err");
?>
