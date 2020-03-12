<?php
include "add_block.php";
include "page_append.php";
require "check_exists.php";
require_once "database.php";

session_start();
$session = 0;
$add_page = "html/404.html";

if (array_key_exists('User_session', $_SESSION))
{
    $session = 1;
    if (isset($_GET['action']))
    {
        if ($_GET['action'] == 'sign_out')
        {
            unset($_SESSION['User_session']);
            $add_page = "html/login.html";
            $session = 0;
        }
        if($_GET['action'] == "image_view")
            $add_page = "html/main.html";
        if ($_GET['action'] == 'change_login')
            $add_page = "html/change_login.html";
        if ($_GET['action'] == 'change_password')
            $add_page = "html/change_password.html";
        if ($_GET['action'] == 'change_email')
            $add_page = "html/change_email.html";
        if ($_GET['action'] == "success")
            $add_page = "html/sign_up_success.html";
        if ($_GET['action'] == "gallery")
            $add_page = "html/gallery.html";
        if ($_GET['action'] == "change_email_2step")
        {
            $token = $_GET['token'];
            if(!check_exists('token', 'EMAIL_CHANGE', $token))
                header("Location: index.php?error=5");
            else {
                $query = "UPDATE USERS as dest,
                (SELECT new_email, user_id FROM EMAIL_CHANGE WHERE token='$token') as src
                SET dest.email=src.new_email WHERE dest.user_id=src.user_id";
                $db->db_change($query);
                $query = "DELETE FROM EMAIL_CHANGE WHERE token='$token'";
                $db->db_change($query);
                $add_page = "html/main.html";
            }
        }
    }
    else
        $add_page = "html/main.html";
}
elseif(isset($_GET['action']))
{
    if ($_GET['action'] == 'sign_up')
        $add_page = "html/sign_up.html";
    if ($_GET['action'] == "restore_password")
        $add_page = "html/restore_password.html";
    if ($_GET['action'] == "success")
        $add_page = "html/sign_up_success.html";
    if ($_GET['action'] == "restore")
        $add_page = "html/restore_password_2step.html";
    if ($_GET['action'] == "change_email_2step")
    {
        $token = $_GET['token'];
        if(!check_exists('token', 'EMAIL_CHANGE', $token))
            header("Location: index.php?error=5");
        else {
            $query = "UPDATE USERS as dest,
                (SELECT new_email, user_id FROM EMAIL_CHANGE WHERE token='$token') as src
                SET dest.email=src.new_email WHERE dest.user_id=src.user_id";
            $db->db_change($query);
            $query = "DELETE FROM EMAIL_CHANGE WHERE token='$token'";
            $db->db_change($query);
        }
    }
    if ($_GET['action'] == "activate")
        {
            if(isset($_GET['token']))
            {
                $token = $_GET['token'];
                $_SESSION['token'] = $token;
                if (check_exists('token', 'USER_TEMP', $token))
                    $add_page = "html/sign_up_2step.html";
                else
                {
                    $err = 5;
                    header("Location: index.php?error=" . $err);
                }
            }
        }
}
else
    $add_page = "html/login.html";
if (isset($_GET['error'])) {
    $append_id = 'help';
    $page_append = "error_block";
    $query = 'SELECT err_text FROM ERR_CODES WHERE err_id=' . $_GET['error'];
    $text = "❗". $db->db_read($query);
}
$page = add_block($add_page, 'main_block', $session);
if(isset($page_append))
    $page = page_append($page, $append_id, $page_append, $text);
print($page->saveHTML());
?>
