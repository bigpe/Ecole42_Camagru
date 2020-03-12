<?php
require_once "database.php";

session_start();
date_default_timezone_set("Europe/Moscow");
header('Content-Type: application/json');
$login = $_SESSION['User_session'];
$user_id = $db->db_read("SELECT user_id FROM USERS WHERE login='$login' or email='$login'");
$offset = $_POST['offset'];
$image_count = $db->db_read("SELECT COUNT(*) FROM USER_PHOTO");
$feed = $db->db_read_multy("SELECT photo_token as image_id, photo_id, photo_src, creation_date FROM USER_PHOTO ORDER BY photo_id DESC LIMIT 5 OFFSET $offset");

$data = array("image_count" => $image_count);

foreach ($feed as $item){
    $photo_id = $item["photo_id"];
    $comment_count = $db->db_read("SELECT COUNT(*) FROM COMMENTS WHERE photo_id='$photo_id'");
    $like_count = $db->db_read("SELECT COUNT(*) FROM LIKES WHERE photo_id='$photo_id'");
    $like_self = $db->db_read("SELECT COUNT(*) FROM LIKES WHERE user_id='$user_id' AND photo_id='$photo_id'");
    #$share_count = $db->db_read("SELECT COUNT(*) FROM SHARES WHERE photo_id='$photo_id'");
    $time = date("j M. g:i" , strtotime($item["creation_date"]));
    $image = 'data: ' . mime_content_type($item['photo_src']) . ';base64,' . base64_encode(file_get_contents($item['photo_src']));
    $data[] = array("image_id" => $item['image_id'],
        "photo_id" => $item['photo_id'],
        "photo_base64" => $image,
        "time" => $time,
        "comment_count" => $comment_count,
        "like_count" => $like_count,
        "like_self" => $like_self);
}

print(json_encode($data));
?>