<?php
require_once "database.php";
require_once "check_exists.php";

session_start();
header('Content-Type: application/json');
$login = $_SESSION['User_session'];
$offset = $_POST['offset'];
$answer_list = json_encode([]);
$answer = 0;

$user_id = $db->db_read("SELECT user_id FROM USERS WHERE email='$login' OR login='$login'");
$image_count = $db->db_read("SELECT COUNT(*) FROM USER_PHOTO WHERE user_id='$user_id'");
if(check_exists("user_id", "USER_PHOTO", "$user_id"))
    $answer = 1;
if ($answer) {
    $answer_list = $db->db_read_multy("SELECT photo_token, photo_src, photo_id FROM USER_PHOTO WHERE user_id='$user_id' ORDER BY photo_id DESC LIMIT 5 OFFSET $offset");
    foreach ($answer_list as $item)
    {
        $photo_token = $item['0'];
        $photo_src = $item['1'];
        $photo_byte = 'data: ' . mime_content_type($photo_src) . ';base64,' . base64_encode(file_get_contents($photo_src));
        $answer_res[] = array("photo_token"  => $photo_token, "photo_byte" => $photo_byte);
    }
    $answer_list = json_encode(array("image_count" => $image_count, "data" => $answer_res));
}
print($answer_list);
?>