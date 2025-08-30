<?php
include('data/database.php');
// use PHPMailer\PHPMailer\PHPMailer;
// use PHPMailer\PHPMailer\Exception;

// require 'PHPMailer/src/Exception.php';
// require 'PHPMailer/src/PHPMailer.php';
// require 'PHPMailer/src/SMTP.php';
// $mail_username = "pykpykdaun69@gmail.com";
// $mail_to = "kanskrop@gmail.com";
// // Adminkanskrop2025!
// try {
//     // Настройки сервера
//     $mail->isSMTP();
//     $mail->Host = 'smtp.gmail.com'; // SMTP сервер
//     $mail->SMTPAuth = true;
//     $mail->Username = $mail_to; // Твоя почта
//     $mail->Password = 'твой_app_password';   // Пароль приложения (Google, Яндекс и т.д.)
//     $mail->SMTPSecure = 'tls';
//     $mail->Port = 587;

//     // От кого
//     $mail->setFrom('твоя_почта@gmail.com', 'Твой сайт');
//     // Кому
//     $mail->addAddress('получатель@mail.com', 'Имя получателя');

//     // Контент
//     $mail->isHTML(true);
//     $mail->Subject = 'Письмо через PHPMailer';
//     $mail->Body = '<h2>Привет, Серёжа!</h2><p>Это письмо ушло через <b>SMTP</b> без Composer.</p>';
//     $mail->AltBody = 'Это письмо ушло через SMTP (без HTML).';

//     $mail->send();
//     echo 'Письмо успешно отправлено!';
// } catch (Exception $e) {
//     echo "Ошибка при отправке: {$mail->ErrorInfo}";
// }
// $mail = new PHPMailer(true);
$data = [
    ["name", "product_name", "price"],
    ["Max", "обоська", "67779"],
    ["Укроп", "чашка", "666788"],
];
$f_name = "cards/card" . time() . ".csv";
print_r($f_name);
$fp = fopen($f_name, "w");
foreach ($data as $key => $value) {
    fputcsv($fp, $value);

}
fclose($fp);
//----------------------------------ОТПРАВКА НА ПОЧТУ-----------------------------------//
$order_sql = "SELECT * FROM admins WHERE id = 1 LIMIT 1 ";
$order_query = $db_conn->query($order_sql);
print_r("3");
if ($order_query && $row = $order_query->fetch_assoc()) {
    $mail_to = $row['email'];
} else {
    die("Не вдалося отримати email одержувача");
}
$mail_host = "smtp.gmail.com";
$mail_username = "timtimplay077@gmail.com";
$mail_to = "timtimplay077@gmail.com";
$firstName = $_GET['firstName'] ?? '';
$lastName = $_GET['lastName'] ?? '';
$email = $_GET['email'] ?? '';
$phone = $_GET['phone'] ?? '';
$city = $_GET['city'] ?? '';
$region = $_GET['region'] ?? '';
$adres = $_GET['adres'] ?? '';
$message = file_get_contents("mail/rekvisit.php");
$message = str_replace('{{first_name}}', $firstName, $message);
$message = str_replace('{{last_name}}', $lastName, $message);
$message = str_replace('{{email}}', $email, $message);
$message = str_replace('{{phone}}', $phone, $message);
$message = str_replace('{{city}}', $city, $message);
$message = str_replace('{{region}}', $region, $message);
$message = str_replace('{{address}}', $adres, $message);
print_r($message);

$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type: text/html; charset=UTF-8" . "\r\n";
$headers .= "From: Серёжа <$mail_username>" . "\r\n";
$headers .= "Reply-To: $mail_username" . "\r\n";
$headers .= "X-Mailer: PHP/" . phpversion();
if (mail($mail_to, "Нове замовлення", $message, $headers)) {
    echo "Замовлення принято!";
} else {
    echo "Помилка при надсиланні листа.";
}
unlink($f_name);