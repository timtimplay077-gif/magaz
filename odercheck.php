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
// Получаем email администратораа
$order_sql = "SELECT * FROM admins WHERE id = 1 LIMIT 1";
$order_query = $db_conn->query($order_sql);
if ($order_query && $row = $order_query->fetch_assoc()) {
    $mail_to = $row['email'];
} else {
    die("Не удалось отримати email одержувача");
}

$firstName = $_GET['firstName'] ?? '';
$lastName = $_GET['lastName'] ?? '';
$email = $_GET['email'] ?? '';
$phone = $_GET['phone'] ?? '';
$city = $_GET['city'] ?? '';
$region = $_GET['region'] ?? '';
$adres = $_GET['adres'] ?? '';
$user_id = $_SESSION['user_id'] ?? 1;
$basket_items = [];
$total_amount = 0;
$total_items = 0;

$basket_sql = "SELECT b.product_id, b.count, p.name, p.price, p.productСode 
               FROM basket b 
               JOIN products p ON b.product_id = p.id 
               WHERE b.user_id = '$user_id'";
$basket_result = $db_conn->query($basket_sql);

if ($basket_result && $basket_result->num_rows > 0) {
    while ($item = $basket_result->fetch_assoc()) {
        $item_total = $item['price'] * $item['count'];
        $basket_items[] = $item;
        $total_amount += $item_total;
        $total_items += $item['count'];
    }
}
$orderInfo = "
🛒 <b>Нове замовлення</b>

👤 <b>Клієнт:</b>
• Ім'я: $firstName $lastName
• Email: $email
• Телефон: $phone

📍 <b>Адреса:</b>
• Місто: $city
• Область: $region
• Адреса: $adres

📦 <b>Замовлення:</b>
";
foreach ($basket_items as $item) {
    $item_total = $item['price'] * $item['count'];
    $product_code = !empty($item['productСode']) ? $item['productСode'] : 'н/д';
    
    $orderInfo .= "
• {$item['name']}
  📦 Код: $product_code
  📊 Кількість: {$item['count']} шт.
  💰 Ціна: {$item['price']} ₴ × {$item['count']} = {$item_total} ₴
";
}

$orderInfo .= "
────────────────
✅ <b>Разом:</b>
• Товарів: $total_items шт.
• Загальна сума: $total_amount ₴
────────────────
";

// ================== ОТПРАВКА В TELEGRAM ==================
function sendTelegram($message)
{
    $token = "7985968026:AAHoNcDbNimVpToWxoYlDskFoBajQ03T5Uc";
    $chat_id = "6596649217";

    $url = "https://api.telegram.org/bot$token/sendMessage";

    $data = [
        'chat_id' => $chat_id,
        'text' => $message,
        'parse_mode' => 'HTML'
    ];

    $options = [
        'http' => [
            'method' => 'POST',
            'header' => "Content-Type: application/x-www-form-urlencoded\r\n",
            'content' => http_build_query($data)
        ]
    ];

    $context = stream_context_create($options);
    $result = file_get_contents($url, false, $context);
    
    return $result !== false;
}
if (sendTelegram($orderInfo)) {
    $clear_sql = "DELETE FROM basket WHERE user_id = '$user_id'";
    $db_conn->query($clear_sql);
    
        header("Location: thank_order.php");
} else {
    echo "Помилка при відправленні замовлення. Спробуйте ще раз.";
}

?>