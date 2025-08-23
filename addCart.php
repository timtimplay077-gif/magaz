<?php
include('data/session_start.php');
include('data/database.php');

header('Content-Type: application/json');

$response = [
    'status' => 'error',
    'message' => '',
    'cart_count' => 0,
    'item' => null
];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $response['message'] = 'invalid_request';
    echo json_encode($response);
    exit;
}

$product_id = intval($_POST['product_id'] ?? 0);
if ($product_id <= 0) {
    $response['message'] = 'invalid_product_id';
    echo json_encode($response);
    exit;
}

// Проверка авторизации
if (!isset($_SESSION['user_id'])) {
    $response['message'] = 'not_logged_in';
    echo json_encode($response);
    exit;
}

$user_id = $_SESSION['user_id'];

try {
    // Проверяем, есть ли такой продукт
    $stmt = $db_conn->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $product_result = $stmt->get_result();
    if ($product_result->num_rows === 0) {
        $response['message'] = 'product_not_found';
        echo json_encode($response);
        exit;
    }
    $product = $product_result->fetch_assoc();
    $stmt->close();

    // Проверяем, есть ли товар уже в корзине
    $stmt = $db_conn->prepare("SELECT count FROM basket WHERE user_id = ? AND product_id = ?");
    $stmt->bind_param("ii", $user_id, $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        // Увеличиваем количество
        $update = $db_conn->prepare("UPDATE basket SET count = count + 1 WHERE user_id = ? AND product_id = ?");
        $update->bind_param("ii", $user_id, $product_id);
        $update->execute();
        $update->close();
        $quantity = $result->fetch_assoc()['count'] + 1;
    } else {
        // Добавляем новый товар
        $insert = $db_conn->prepare("INSERT INTO basket (user_id, product_id, count) VALUES (?, ?, 1)");
        $insert->bind_param("ii", $user_id, $product_id);
        $insert->execute();
        $insert->close();
        $quantity = 1;
    }
    $stmt->close();

    // Получаем актуальное количество товаров в корзине
    $stmt = $db_conn->prepare("SELECT SUM(count) as total FROM basket WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $count_result = $stmt->get_result()->fetch_assoc();
    $cart_count = $count_result['total'] ?? 0;
    $stmt->close();

    // Считаем цену с модификатором
    $modifier = $product['price_modifier'] ?? 0;
    $price = $product['price'] * (1 + $modifier / 100);

    $response['status'] = 'success';
    $response['message'] = 'added_to_cart';
    $response['cart_count'] = $cart_count;
    $response['item'] = [
        'id' => $product['id'],
        'name' => $product['name'],
        'img' => $product['img'],
        'price' => $price,
        'quantity' => $quantity,
        'total' => $price * $quantity
    ];

} catch (Exception $e) {
    $response['message'] = 'database_error';
}

echo json_encode($response);
exit;
?>