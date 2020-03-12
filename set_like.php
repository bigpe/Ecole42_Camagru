<?php
require_once "database.php";
require_once "check_exists.php";

session_start();
$login = $_SESSION['User_session'];
$user_id = $db->db_read("SELECT user_id FROM USERS WHERE login='$login' OR email='$login'");
$image_id = $_POST['image_id'];
$photo_id = $db->db_read("SELECT photo_id FROM USER_PHOTO WHERE photo_token='$image_id'");
$action = $_POST['action'];

if ($action == "like" && !check_exists(array("user_id", "photo_id"), "LIKES", array($user_id, $photo_id)))
    $query = "INSERT INTO LIKES (photo_id, user_id) VALUES ('$photo_id', '$user_id')";
else
    $query = "DELETE FROM LIKES WHERE user_id='$user_id' AND photo_id='$photo_id'";
$db->db_change($query);
header("Content-Type: application/json");
print(json_encode(array("image_id" => $image_id, "user_id" => $user_id)));
?>