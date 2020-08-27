<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require $_SERVER['DOCUMENT_ROOT'] . '/mail/Exception.php';
require $_SERVER['DOCUMENT_ROOT'] . '/mail/PHPMailer.php';
require $_SERVER['DOCUMENT_ROOT'] . '/mail/SMTP.php';

function send_mail($email, $msg)
{
    $mail = new PHPMailer;
    $mail->isSMTP();
    $mail->SMTPDebug = 0;
    $mail->Host = "smtp.yandex.ru"; //For Example, Yandex.ru mail settings
    $mail->Port = 587;
    $mail->SMTPSecure = 'tls';
    $mail->SMTPAuth = true;
    $mail->Username = 'YOUR_MAIL';
    $mail->Password = 'YOUR PASS';
    $mail->setFrom('YOUR_MAIL', 'Camagru');
    $mail->addAddress("$email");
    $mail->Subject = 'Camagru System Message';
    $mail->msgHTML($msg);
    $mail->AltBody = 'HTML messaging not supported';
    $mail->SMTPOptions = array(
        'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        )
    );
    if ($mail->send())
        return(1);
    else
        return (0);
}