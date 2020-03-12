<?php
require_once "database.php";

session_start();
$src = 'img/' . time() . ".jpg";
$login = $_SESSION['User_session'];
$image = $_POST['image'];
$photo_token = $_POST['photo_token'];
$filter = $_POST['filter'];
$filter_src = "filters/filter$filter.png";

$image_decode = file_get_contents(base64_decode($image));
file_put_contents($src, $image_decode);
if ($filter) {
    $filter_image = imagecreatefrompng($filter_src);
    imagesavealpha($filter_image, true);
    if ($filter != "3" && $filter != "4" && $filter != "5") {
        $filter_image = imagescale($filter_image, 150, 150);
        $offset = 0;
    }
    if ($filter == "5") {
        $filter_image = imagescale($filter_image, 100, 150);
        $offset = -25;
    }
    if ($filter == "3" || $filter == "4") {
        $filter_image = imagescale($filter_image, 180, 150);
        $offset = 15;
    }
    $filter_width = imagesx($filter_image);
    $filter_height = imagesy($filter_image);
    $image_original = imagecreatefromjpeg($src);
    imagecopy($image_original, $filter_image, 100 - $offset, 20, 0, 0, $filter_width, $filter_height);
    imagejpeg($image_original, $src, 100);
}

$query = "INSERT INTO USER_PHOTO(photo_src, photo_token, user_id, filter_id)
SELECT '$src', '$photo_token', user_id, '$filter'
FROM USERS WHERE login='$login' or email='$login'";
$db->db_change($query);
?>