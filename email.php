<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

if (isset($_POST["send"])) {
    $mail = new PHPMailer(true);
    
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'chuzuchum@gmail.com'; // your email
        $mail->Password = 'jtzf wkue vkqb yplq'; // your email password
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465;

        $mail->setFrom('chuzuchum@gmail.com', 'Hentoki Customer: ' . $_POST["name"]); // your email
        $mail->addAddress('yvontagum@gmail.com');

        $mail->isHTML(true);
        $mail->Subject = $_POST["subject"];
        $mail->Body = $_POST["message"];

        $mail->send();
        
        echo
        "
        <script>
            alert('Sent Successfully!');
            document.location.href = 'emailForm.php';
        </script>
        ";
    } catch (Exception $e) {
        echo
        "
        <script>
            alert('Message could not be sent. Mailer Error: {$mail->ErrorInfo}');
            document.location.href = 'emailForm.php';
        </script>
        ";
    }
}
?>
