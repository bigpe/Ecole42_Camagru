<?php
session_start();
$session = 0;

if(array_key_exists('User_session', $_SESSION))
    $session = 1;
function create_body()
{
    return("<!DOCTYPE html>
<html lang=\"en\">
<head>
    <script src=\"https://kit.fontawesome.com/4c208c6ea5.js\" crossorigin=\"anonymous\"></script>
    <meta charset=\"UTF-8\">
    <meta name=\"viewport\" content=\"width=device-width initial-scale=1.0\">
    <title>Camagru</title>
    <link rel=\"stylesheet\" type=\"text/css\" href=\"../style/style.css\">
</head>
<body>");
}

function create_header()
{
    global $session;
    $session_var = "none";
    if($session)
        $session_var = "inherit";
    return("<div id=\"wrap\">
    <div id=\"header\">
        <div class=\"header_button\" id=\"gallery\" style=\"display: $session_var\"><a href=\"index.php?action=gallery\"><p><i class=\"fas fa-images\"></i></p></a></div>
        <div id=\"logo\" onclick=\"window.location='/'\" style=\"cursor: pointer;\"><a><p><i class=\"fas fa-camera-retro\"></i></p></a></div>
        <div class=\"sett_but\" id=\"profile_sht\" style=\"display: $session_var\"><a href=\"/settings.php\"><p><i class=\"fas fa-wrench\"></i></p></a></div>
        <div class=\"sett_but\" id=\"sign_out\"><a href=\"/?action=sign_out\"><p><i class=\"fas fa-sign-out-alt\"></i></p></a></div>
    </div>
    <div id=\"main_block\">");
}
function create_footer()
{
    return('</div>
</div>
<div id="footer">
    <i id="mouse" class="fas fa-mouse-pointer"></i>
    <div id="footer_logo">
        <a href="https://vk.com/bigpe"><i class="fab fa-vk"></i></a>
        <a href="https://telegram.me/bigpebro"><i class="fab fa-telegram-plane"></i></a>
        <a href="https://github.com/bigpe"><i class="fab fa-github-alt"></i></a>
    </div>
</div>
</body>
</html>');
}
?>