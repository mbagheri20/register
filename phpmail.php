<?php 
error_reporting(-1);
ini_set('display_errors', 'On');
set_error_handler("var_dump");
$email = "mohammad.bagheri@oulu.fi";
$subject =  "Email Test";
$message = "this is a mail testing email function on server";


$sendMail = mail($email, $subject, $message);
if($sendMail)
{
echo "Email Sent Successfully";
}
else

{
echo "Mail Failed";
}
?>
