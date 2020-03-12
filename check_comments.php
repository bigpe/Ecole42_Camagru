<?php
require_once "check_exists.php";
require_once "database.php";

header("Content-type: application/json");
session_start();
$login = $_SESSION['User_session'];
$image_id = $_POST['image_id'];
$photo_id = $db->db_read("SELECT photo_id FROM USER_PHOTO WHERE photo_token='$image_id'");
$user_id = $db->db_read("SELECT user_id FROM USERS WHERE login='$login' OR email='$login'");
$comment_count = (int)$db->db_read("SELECT COUNT(*) FROM COMMENTS WHERE photo_id='$photo_id'");


$result = array("CommentCount" => $comment_count);
print(json_encode($result));
?>