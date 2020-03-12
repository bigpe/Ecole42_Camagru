<?php
require_once "database.php";
require_once "check_exists.php";

session_start();
$answer = null;
$login = $_SESSION['User_session'];
$photo_token = $_POST['image_id'];

$user_id = $db->db_read("SELECT user_id FROM USERS WHERE login='$login' OR email='$login'");
$col = array("photo_token", "user_id");
$entry = array($photo_token, $user_id);
if(check_exists($col, "USER_PHOTO", $entry)) {
    $photo_src = $db->db_read("SELECT photo_src FROM USER_PHOTO WHERE photo_token='$photo_token'");
    $photo_byte = 'data: ' . mime_content_type($photo_src) . ';base64,' . base64_encode(file_get_contents($photo_src));
    $answer = array("image_src" => $photo_byte);
}
print(json_encode($answer));
?>