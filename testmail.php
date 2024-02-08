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
    $mail->Host = 'smtp.pouta.csc.fi';
    $mail->SMTPAuth = false;
    $mail->SMTPAutoTLS = false; 
    $mail->Port = 25; 
    
    //$mail->isSMTP();                                            //Send using SMTP
    //$mail->Host       = 'smtp.pouta.csc.fi';                     //Set the SMTP server to send through
    //$mail->SMTPAuth   = false;
    //$mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
    //$mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    //Recipients
    //$mail->Sender='mohammad.bagheri@oulu.fi';
    $mail->setFrom('mohammad.bagheri@oulu.fi', 'Physics Boat');
    $mail->addAddress('mohammad.bagheri@student.oulu.fi', 'User');     //Add a recipient
    $mail->addReplyTo('mohammad.bagheri@oulu.fi', 'Information');

    //Content
    //$mail->SMTPSecure = 'tls';
    //$mail->From = 'mohammad.bagheri@oulu.fi';
    //$mail->FromName = "Any Name";
    //$mail->AddReplyTo('xyz@domainname.com', 'any name'); 
    //$mail->AddAddress($to['email'],$to['name']);
    //$mail->Priority = 1;
    //$mail->AddCustomHeader("X-MSMail-Priority: High");
    //$mail->WordWrap = 50; 
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = 'first project report Nov';
    $mail->Body    = 'Hi, If you received this email, it means the PHP mail fixed!';
    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    $mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
