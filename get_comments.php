<?php
require_once "database.php";

header("Content-Type: application/json");
date_default_timezone_set("Europe/Moscow");

$image_id = $_POST['image_id'];
$photo_id = $db->db_read("SELECT photo_id FROM USER_PHOTO WHERE photo_token='$image_id'");

$comments = $db->db_read_multy("SELECT comment_text, login, COMMENTS.creation_date FROM COMMENTS 
    JOIN USERS ON USERS.user_id=COMMENTS.user_id WHERE photo_id='$photo_id' ORDER BY comment_id DESC");

$result = new ArrayObject();

foreach ($comments as $comment) {
    $time = date("j M. g:i" , strtotime($comment["creation_date"]));
    $result[] = array("username" => $comment["login"], "text" => $comment["comment_text"],
        "time" => $time);
}
print(json_encode($result));
?>