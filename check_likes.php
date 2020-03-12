<?php
require_once "check_exists.php";
require_once "database.php";

header("Content-type: application/json");
session_start();
$login = $_SESSION['User_session'];
$image_id = $_POST['image_id'];
$photo_id = $db->db_read("SELECT photo_id FROM USER_PHOTO WHERE photo_token='$image_id'");
$user_id = $db->db_read("SELECT user_id FROM USERS WHERE login='$login' OR email='$login'");
$likes_count = (int)$db->db_read("SELECT COUNT(*) FROM LIKES WHERE photo_id='$photo_id'");


$result = array("LikeSelf" => 0, "LikeCount" => $likes_count);
if(check_exists(array("user_id", "photo_id"), "LIKES", array($user_id, $photo_id)))
    $result["LikeSelf"] = 1;
print(json_encode($result));
?>