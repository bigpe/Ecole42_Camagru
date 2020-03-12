<?php
require_once "database.php";
require_once "check_exists.php";

session_start();
$login = $_SESSION['User_session'];
$photo_token = $_POST['photo_token'];

$query = "SELECT user_id FROM USERS WHERE login='$login' OR email='$login'";
$user_id = $db->db_read($query);

$col = array("user_id", "photo_token");
$entry = array($user_id, $photo_token);
if(check_exists($col, 'USER_PHOTO', $entry)) {
    $image_src = $db->db_read("SELECT photo_src FROM USER_PHOTO WHERE photo_token='$photo_token'");
    $db->db_change("DELETE FROM USER_PHOTO WHERE photo_token='$photo_token'");
    shell_exec("rm $image_src");
}
?>