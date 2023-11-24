<?php 

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
