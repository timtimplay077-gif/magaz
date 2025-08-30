<?php
include('data/database.php');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

$mail = new PHPMailer(true);

try {
    // Сервер
    $mail->isSMTP();
    $mail->Host = 'smtp.hostinger.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'admin@kanskrop.com'; // почта Hostinger
    $mail->Password = 'Adminkanskrop2025!';        // пароль
    $mail->SMTPSecure = 'ssl'; // можно 'tls', но чаще ssl
    $mail->Port = 465;

    // Отправитель
    $mail->setFrom('noreply@kanskrop.com', 'Мой сайт');
    // Получатель
    $mail->addAddress('admin@kanskrop.com', 'Сергей');

    // Контент
    $mail->isHTML(true);
    $mail->Subject = 'Тестовое письмо с Hostinger';
    $mail->Body = '<h2>Привет, Серёжа!</h2><p>Это письмо пришло через SMTP Hostinger 🚀</p>';
    $mail->AltBody = 'Это тестовое письмо с Hostinger (без HTML).';

    $mail->send();
    echo 'Письмо успешно отправлено!';
} catch (Exception $e) {
    echo "Ошибка: {$mail->ErrorInfo}";
}
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
// Adminkanskrop2025!
//----------------------------------ОТПРАВКА НА ПОЧТУ-----------------------------------//
$order_sql = "SELECT * FROM admins WHERE id = 1 LIMIT 1 ";
$order_query = $db_conn->query($order_sql);
print_r("3");
if ($order_query && $row = $order_query->fetch_assoc()) {
    $mail_to = $row['email'];
} else {
    die("Не вдалося отримати email одержувача");
}
$mail_host = "pop.hostinger.com";
$mail_username = "admin@kanskrop.com";
$mail_to = "admin@kanskrop.com";
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