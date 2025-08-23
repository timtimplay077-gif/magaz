<?php
include('data/session_start.php');
include('data/database.php');
if (!isset($_SESSION['user_id'])) {
    die("Ви не авторизовані");
}
$user_id = $_SESSION['user_id'];
$firstName = trim($_POST['firstName'] ?? '');
$lastName = trim($_POST['lastName'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$city = trim($_POST['city'] ?? '');
$region = trim($_POST['region'] ?? '');
$address = trim($_POST['address'] ?? '');
$basket_items = [];
$total_amount = 0;
$total_items = 0;

$basket_sql = "SELECT b.product_id, b.count, p.name, p.price, p.productСode AS productCode
               FROM basket b 
               JOIN products p ON b.product_id = p.id 
               WHERE b.user_id = ?";

$stmt = $db_conn->prepare($basket_sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

while ($item = $result->fetch_assoc()) {
    $basket_items[] = $item;
    $total_items += $item['count'];
    $total_amount += $item['price'] * $item['count'];
}
$stmt->close();

if (empty($basket_items)) {
    die("Кошик порожній");
}
$orderInfo = "🛒 <b>Нове замовлення</b>\n\n";
$orderInfo .= "👤 <b>Клієнт:</b>\n";
$orderInfo .= "• Ім'я: $firstName\n• Прізвище: $lastName\n• Email: $email\n• Телефон: $phone\n\n";
$orderInfo .= "📍 <b>Адреса:</b>\n• Місто: $city\n• Регіон: $region\n• Адреса: $address\n\n";
$orderInfo .= "📦 <b>Замовлення:</b>\n";

foreach ($basket_items as $item) {
    $item_total = $item['price'] * $item['count'];
    $product_code = $item['productCode'] ?? 'н/д';
    $orderInfo .= "• {$item['name']}\n  📦 Код: $product_code\n  📊 Кількість: {$item['count']} шт.\n  💰 Ціна: {$item['price']} ₴ × {$item['count']} = {$item_total} ₴\n";
}

$orderInfo .= "────────────────\n✅ <b>Разом:</b>\n• Товарів: $total_items шт.\n• Загальна сума: $total_amount ₴\n────────────────";
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
    return file_get_contents($url, false, $context) !== false;
}

if (sendTelegram($orderInfo)) {
    // Очистка корзины
    $clear_sql = "DELETE FROM basket WHERE user_id = ?";
    $stmt = $db_conn->prepare($clear_sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->close();

    header("Location: thank_order.php");
    exit;
} else {
    echo "Помилка при відправленні замовлення. Спробуйте ще раз.";
}
?>