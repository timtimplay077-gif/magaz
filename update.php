<?php
include("data/database.php");
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Не авторизований']);
    exit;
}

$user_id = $_SESSION['user_id'];
$product_id = intval($_POST['product_id'] ?? 0);
$action = $_POST['action'] ?? '';
$new_quantity = intval($_POST['quantity'] ?? 0);

if ($product_id === 0 || empty($action) || $new_quantity === 0) {
    echo json_encode(['error' => 'Неверные параметры']);
    exit;
}

$stmt = $db_conn->prepare("UPDATE basket SET count = ? WHERE user_id = ? AND product_id = ?");
$stmt->bind_param("iii", $new_quantity, $user_id, $product_id);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'quantity' => $new_quantity]);
} else {
    echo json_encode(['error' => 'Ошибка базы данных']);
}

$stmt->close();
?>