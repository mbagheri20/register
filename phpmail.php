<?php
$sender = 'haa@s2i-php-container-boat.rahtiapp.fi/';
$recipient = 'mohammad.bagheri@oulu.fi';

$subject = "php mail test";
$message = "php test message";
$headers = 'From:' . $sender;

if (mail($recipient, $subject, $message, $headers))
{
    echo "Message accepted";
}
else
{
    echo "Error: Message not accepted";
}
?>
