<?php
$mail_host = "smtp.gmail.com";
$mail_username = "pykpykdaun69@gmail.com";
$mail_to = "timtimplay077@gmail.com";
$message = "hello";
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type: text/html; charset=UTF-8" . "\r\n";
$headers .= "From: Серёжа <$mail_username>" . "\r\n";
$headers .= "Reply-To: $mail_username" . "\r\n";
$headers .= "X-Mailer: PHP/" . phpversion();
mail($mail_to , "hello" , $message , $headers);

?>