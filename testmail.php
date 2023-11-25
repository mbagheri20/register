<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require '/opt/app-root/src/PHPMailer-master/src/Exception.php';
require '/opt/app-root/src/PHPMailer-master/src/PHPMailer.php';
require '/opt/app-root/src/PHPMailer-master/src/SMTP.php';


//Create an instance; passing `true` enables exceptions
$mail = new PHPMailer(true);

try {
    //Server settings
    $mail->SMTPDebug = SMTP::DEBUG_SERVER;
    $mail->isSMTP();
    $mail->Host = 'localhost';
    $mail->SMTPAuth = false;
    $mail->SMTPAutoTLS = false; 
    $mail->Port = 25; 
    
    //$mail->isSMTP();                                            //Send using SMTP
    //$mail->Host       = 'smtp.pouta.csc.fi';                     //Set the SMTP server to send through
    //$mail->SMTPAuth   = false;
    //$mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
    //$mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    //Recipients
    $mail->setFrom('mohammad.bagheri@oulu.fi', 'Mailer');
    $mail->addAddress('mohammad.bagheri.fi@gmail.com', 'Joe User');     //Add a recipient
    $mail->addReplyTo('mohammad.bagheri@oulu.fi', 'Information');

    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = 'Here is the subject';
    $mail->Body    = 'This issdsdsdsdsdsd the HTML message body <b>in bold!</b>';
    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    $mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
