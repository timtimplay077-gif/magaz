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
$user_sql = "SELECT * FROM users WHERE id = ?";
$user_stmt = $db_conn->prepare($user_sql);
$user_stmt->bind_param("i", $user_id);
$user_stmt->execute();
$user_result = $user_stmt->get_result();
$user_row = $user_result->fetch_assoc();
$user_sale = $user_row['sale'] ?? 0;
$user_stmt->close();
if (empty($firstName) || empty($lastName) || empty($email) || empty($phone) || empty($address)) {
    die("Заповніть обов'язкові поля: ім'я, прізвище, email, телефон, адреса");
}
$user_sale = $user_row['sale'] ?? 0;


if ($isLoggedIn && $user_sale != 10) {
    $update_sql = "UPDATE users SET sale = 10 WHERE id = ?";
    $update_stmt = $db_conn->prepare($update_sql);
    $update_stmt->bind_param("i", $user_id);
    $update_stmt->execute();
    $update_stmt->close();
    $user_sale = 10;
}
$basket_sql = "SELECT b.product_id, b.count, p.name, p.price, p.price_modifier, p.productСode AS productCode
               FROM basket b 
               JOIN products p ON b.product_id = p.id 
               WHERE b.user_id = ?";

$stmt = $db_conn->prepare($basket_sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
if (!empty($user_sale)) {
    $orderInfo .= "🎫 Ваша скидка: $user_sale%\n\n";
}
while ($item = $result->fetch_assoc()) {
    // Рассчитываем итоговую цену со всеми скидками
    $price = $item['price'];

    // Применяем модификатор цены товара (если есть)
    if (!empty($item['price_modifier'])) {
        $price *= (1 + $item['price_modifier'] / 100);
    }

    // Применяем скидку пользователя (если есть)
    if (!empty($user_sale)) {
        $price *= (1 - $user_sale / 100);
    }

    $item['final_price'] = $price;
    $item_total = $price * $item['count'];

    $basket_items[] = $item;
    $total_items += $item['count'];
    $total_amount += $item_total;
}
$stmt->close();

if (empty($basket_items)) {
    die("Кошик порожній");
}

$orderInfo = "🛒 Нове замовлення \n\n";
$orderInfo .= "👤 Клієнт: \n";
$orderInfo .= "• Ім'я: $firstName\n• Прізвище: $lastName\n• Email: $email\n• Телефон: $phone\n\n";
$orderInfo .= "📍 Адреса: \n• Місто: $city\n• Регіон: $region\n• Адреса: $address\n\n";

// Добавляем информацию о скидках
if (!empty($user_sale)) {
    $orderInfo .= "🎫 Скидка пользователя: $user_sale%\n\n";
}

$orderInfo .= "📦 Замовлення: \n";

foreach ($basket_items as $item) {
    $item_total = $item['final_price'] * $item['count'];
    $product_code = $item['productCode'] ?? 'н/д';

    // Добавляем информацию о скидках товара
    $discount_info = "";
    if (!empty($item['price_modifier'])) {
        $modifier_type = $item['price_modifier'] > 0 ? "надбавка" : "скидка";
        $discount_info = " ($modifier_type: " . abs($item['price_modifier']) . "%)";
    }

    $orderInfo .= "• {$item['name']}$discount_info\n   📦 Код: *$product_code*\n   📊 Кількість: {$item['count']} шт.\n   💰 Ціна: {$item['final_price']} ₴ × {$item['count']} = {$item_total} ₴\n\n";
}

$orderInfo .= "────────────────\n✅ Разом:\n• Товарів: $total_items шт.\n• Загальна сума: $total_amount ₴\n────────────────";

function sendTelegram($message)
{
    $token = "8418965565:AAFBJEFWZkN_WiQ7yoq9wlpaqLTMnRjyVAo";
    $chat_id = "8055379494";
    $url = "https://api.telegram.org/bot$token/sendMessage?chat_id=$chat_id&text=" . urlencode($message);
    $response = file_get_contents($url);
    return $response !== false;
}

if (sendTelegram($orderInfo)) {
    // Сохраняем данные в сессии для send_email.php (с учетом скидок)
    $_SESSION['order_data'] = [
        'firstName' => $firstName,
        'lastName' => $lastName,
        'email' => $email,
        'phone' => $phone,
        'city' => $city,
        'region' => $region,
        'adres' => $address,
        'basket_items' => $basket_items,
        'total_amount' => $total_amount,
        'user_sale' => $user_sale
    ];

    // Очищаем корзину
    $clear_sql = "DELETE FROM basket WHERE user_id = ?";
    $stmt = $db_conn->prepare($clear_sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->close();

    // Перенаправляем на отправку email
    header("Location: send_email.php");
    exit;
} else {
    echo "Помилка при відправленні замовлення. Спробуйте ще раз.";
}
?>