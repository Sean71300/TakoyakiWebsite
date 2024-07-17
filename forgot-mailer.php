<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

$mail = new PHPMailer(true);
$mail->isSMTP();
$mail->SMTPAuth = true;

$mail->Host = 'smtp.gmail.com'; //SMTP email address suffix identifier
$mail->Username = 'chuzuchum@gmail.com'; // your email
$mail->Password = 'jtzf wkue vkqb yplq'; // your email password
$mail->SMTPSecure = 'ssl';
$mail->Port = 465;

$mail->isHTML(true);
return $mail; 
?>