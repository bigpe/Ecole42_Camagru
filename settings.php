<?php
include "create_main_page.php";
require_once "database.php";

if(!isset($_SESSION['User_session']))
    header("Location: index.php");
if(strpos($_SESSION['User_session'], "@"))
{
    $email = $_SESSION['User_session'];
    $query = "SELECT login FROM USERS WHERE email='$email'";
    $login = $db->db_read($query);
}
else {
    $login = $_SESSION['User_session'];
    $query = "SELECT email FROM USERS WHERE login='$login'";
    $email = $db->db_read($query);
}


print(create_body());
print(create_header());
print("<meta charset=\"UTF-8\">
<script type='text/javascript' src='js/settings.js'></script>
<div id=\"login\">
    <div><h2 id=\"Welcome\">Your profile</h2></div>
    <div style='background-color: black; width: 50%; margin-left: 25%; border-radius: 10px; border: 2px solid white'>
    <br>
    <h2 onclick=\"window.location='index.php?action=change_login'\" style=\"cursor: pointer\">Change Login <i class=\"fas fa-pencil-alt\"></i></h2>
    <br>
    <p><i class=\"fas fa-user\"></i> $login</p><br>
    <h2 onclick=\"window.location='index.php?action=change_email'\" style=\"cursor: pointer\">Change Email <i class=\"fas fa-pencil-alt\"></i></h2>
    <br>
    <p><i class=\"fas fa-envelope\"></i> $email</p><br>
    <h2 onclick=\"window.location='index.php?action=change_password'\" style=\"cursor: pointer\">Change Password <i class=\"fas fa-pencil-alt\"></i></h2><br>
    <h2 id='email_notify' style=\"cursor: pointer\"><i class=\"fas fa-envelope\"></i> Disable Email Notify <i class=\"fas fa-bell\"></i></h2><br>
    </div></div>");
print(create_footer());
?>