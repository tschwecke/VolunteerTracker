<?php
$to      = 'tim_schwecke@hotmail.com';
$subject = 'Test';
$message = 'This is a test email3';
$headers = 'From: do_not_reply@aspenviewacademy.org';

echo mail($to, $subject, $message, $headers);
?>

Mail sent