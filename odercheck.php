<?php
$mail_host = "smtp.gmail.com";
$mail_username = "pykpykdaun69@gmail.com";
$mail_to = "timtimplay077@gmail.com";
$message = "
<html>
<head>
<title>Тест</title>
<style>
  body {
    font-family: Arial, sans-serif;
    background-color: #f9f9f9;
    color: #333333;
    margin: 0; padding: 20px;
  }
  h1 {
    color: #4CAF50;
    font-size: 24px;
    margin-bottom: 10px;
  }
  p {
    font-size: 16px;
    line-height: 1.5;
    margin-top: 0;
  }
  .container {
    background-color: #ffffff;
    border-radius: 8px;
    padding: 20px;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
    max-width: 600px;
    margin: auto;
  }
</style>
</head>
<body>
  <div class='container'>
    <h1>Привет, пупсик!</h1>
    <p>Это тестовое письмо через mail() на Gmail.</p>
  </div>
</body>
</html>";
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type: text/html; charset=UTF-8" . "\r\n";
$headers .= "From: Серёжа <$mail_username>" . "\r\n";
$headers .= "Reply-To: $mail_username" . "\r\n";
$headers .= "X-Mailer: PHP/" . phpversion();
mail($mail_to, "hello", $message, $headers);

?>