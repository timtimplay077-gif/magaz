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

// Проверка обязательных полей
if (empty($firstName) || empty($lastName) || empty($email) || empty($phone) || empty($address)) {
    die("Заповніть обов'язкові поля: ім'я, прізвище, email, телефон, адреса");
}

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

$orderInfo = "🛒 Нове замовлення \n\n";
$orderInfo .= "👤 Клієнт: \n";
$orderInfo .= "• Ім'я: $firstName\n• Прізвище: $lastName\n• Email: $email\n• Телефон: $phone\n\n";
$orderInfo .= "📍 Адреса: \n• Місто: $city\n• Регіон: $region\n• Адреса: $address\n\n";
$orderInfo .= "📦 Замовлення: \n";

foreach ($basket_items as $item) {
    $item_total = $item['price'] * $item['count'];
    $product_code = $item['productCode'] ?? 'н/д';
    $orderInfo .= "• {$item['name']}\n   📦 Код: *$product_code*\n   📊 Кількість: {$item['count']} шт.\n   💰 Ціна: {$item['price']} ₴ × {$item['count']} = {$item_total} ₴\n\n";
}

$orderInfo .= "────────────────\n✅ Разом:\n• Товарів: $total_items шт.\n• Загальна сума: $total_amount ₴\n────────────────";

function sendTelegram($message) {
    $token = "8418965565:AAFBJEFWZkN_WiQ7yoq9wlpaqLTMnRjyVAo";
    $chat_id = "8055379494";
    $url = "https://api.telegram.org/bot$token/sendMessage?chat_id=$chat_id&text=" . urlencode($message);
    $response = file_get_contents($url);
    return $response !== false;
}

if (sendTelegram($orderInfo)) {
    // Очищаем корзину
    $clear_sql = "DELETE FROM basket WHERE user_id = ?";
    $stmt = $db_conn->prepare($clear_sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->close();

    // Сохраняем данные в сессии для send_email.php
    $_SESSION['order_data'] = [
        'firstName' => $firstName,
        'lastName' => $lastName,
        'email' => $email,
        'phone' => $phone,
        'city' => $city,
        'region' => $region,
        'adres' => $address,
        'basket_items' => $basket_items,
        'total_amount' => $total_amount
    ];

    // Перенаправляем на отправку email
    header("Location: send_email.php");
    exit;
} else {
    echo "Помилка при відправленні замовлення. Спробуйте ще раз.";
}
?>