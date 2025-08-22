<?php
include('data/database.php');
// $order_sql = "SELECT * FROM admins WHERE id = 1 LIMIT 1 ";
// $order_query = $db_conn->query($order_sql);
// if ($order_query && $row = $order_query->fetch_assoc()) {
//     $mail_to = $row['email'];
// } else {
//     die("Не вдалося отримати email одержувача");
// }
// $mail_host = "smtp.gmail.com";
// $mail_username = "admin@kanskrop.com";
// // $mail_to = "timtimplay077@gmail.com";
// $firstName = $_GET['firstName'] ?? '';
// $lastName = $_GET['lastName'] ?? '';
// $email = $_GET['email'] ?? '';
// $phone = $_GET['phone'] ?? '';
// $city = $_GET['city'] ?? '';
// $region = $_GET['region'] ?? '';
// $adres = $_GET['adres'] ?? '';
// $message = file_get_contents("mail/rekvisit.php");
// $message = str_replace('{{first_name}}', $firstName, $message);
// $message = str_replace('{{last_name}}', $lastName, $message);
// $message = str_replace('{{email}}', $email, $message);
// $message = str_replace('{{phone}}', $phone, $message);
// $message = str_replace('{{city}}', $city, $message);
// $message = str_replace('{{region}}', $region, $message);
// $message = str_replace('{{address}}', $adres, $message);
// print_r($message);
// $headers = "MIME-Version: 1.0" . "\r\n";
// $headers .= "Content-type: text/html; charset=UTF-8" . "\r\n";
// $headers .= "From: Серёжа <$mail_username>" . "\r\n";
// $headers .= "Reply-To: $mail_username" . "\r\n";
// $headers .= "X-Mailer: PHP/" . phpversion();
// if (mail($mail_to, "Нове замовлення", $message, $headers)) {
//     echo "Замовлення принято!";
// } else {
//     echo "Помилка при надсиланні листа.";
// }
// Получаем email администратора
$order_sql = "SELECT * FROM admins WHERE id = 1 LIMIT 1";
$order_query = $db->query($order_sql);
if ($order_query && $row = $order_query->fetch_assoc()) {
    $mail_to = $row['email'];
} else {
    die("Не удалось отримати email одержувача");
}

// Данные SMTP (если захотите через PHPMailer)
$mail_host = "smtp.gmail.com";
$mail_username = "admin@knaskrop.com";

// Получаем данные из формы
$firstName = $_GET['firstName'] ?? '';
$lastName = $_GET['lastName'] ?? '';
$email = $_GET['email'] ?? '';
$phone = $_GET['phone'] ?? '';
$city = $_GET['city'] ?? '';
$region = $_GET['region'] ?? '';
$adres = $_GET['adres'] ?? '';

// Формируем текст заказа
$orderInfo = "
Нове замовлення:
Ім'я: $firstName $lastName
Email: $email
Телефон: $phone
Місто: $city
Область: $region
Адреса: $adres
";

// ================== ОТПРАВКА НА ПОЧТУ ==================
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type: text/plain; charset=UTF-8" . "\r\n";
$headers .= "From: Магазин <$mail_username>\r\n";
$headers .= "Reply-To: $mail_username\r\n";

if (mail($mail_to, "Нове замовлення", $orderInfo, $headers)) {
    echo "Замовлення відправлено на email<br>";
} else {
    echo "Помилка при надсиланні листа<br>";
}

// ================== ОТПРАВКА В TELEGRAM ==================
function sendTelegram($message)
{
    $token = "ВАШ_BOT_TOKEN";  // <- замените
    $chat_id = "ВАШ_CHAT_ID";  // <- замените
    $url = "https://api.telegram.org/bot$token/sendMessage";

    $data = [
        'chat_id' => $chat_id,
        'text' => $message,
        'parse_mode' => 'HTML'
    ];

    file_get_contents($url . "?" . http_build_query($data));
}

sendTelegram($orderInfo);

// ================== ОТПРАВКА В VIBER (по желанию) ==================
function sendViber($message)
{
    $token = "ВАШ_VIBER_TOKEN";     // <- замените
    $receiver = "ВАШ_USER_ID";      // <- замените
    $url = "https://chatapi.viber.com/pa/send_message";

    $data = [
        "receiver" => $receiver,
        "type" => "text",
        "text" => $message
    ];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "X-Viber-Auth-Token: $token",
        "Content-Type: application/json"
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    return $response;
}

// Если хотите активировать — раскомментируйте:
// sendViber($orderInfo);

?>