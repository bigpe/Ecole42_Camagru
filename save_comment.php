<?php
require_once "database.php";
require_once "mail.php";

session_start();
$login = $_SESSION['User_session'];
$user_id = $db->db_read("SELECT user_id FROM USERS WHERE login='$login' OR email='$login'");
$image_id = $_POST['image_id'];
$user_id_by_photo = $db->db_read("SELECT user_id FROM USER_PHOTO WHERE photo_token='$image_id'");
if ($user_id != $user_id_by_photo) {
    $email_notify_status = $db->db_read("SELECT email_notify FROM USERS WHERE user_id='$user_id_by_photo'");
    if ($email_notify_status){
        $email = $db->db_read("SELECT email FROM USERS WHERE user_id='$user_id_by_photo'");
        send_mail($email, "We have a new message at your photo, check it out!");
    }
}
$photo_id = $db->db_read("SELECT photo_id FROM USER_PHOTO WHERE photo_token='$image_id'");
$message = $_POST['message'];

$db->db_change("INSERT INTO COMMENTS (photo_id, user_id, comment_text) VALUES ('$photo_id', '$user_id', '$message')");

?>