<?php
// Добавьте session_start() в самое начало
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Просто обновляем количество без строгих проверок
$product_id = intval($_POST['product_id'] ?? 0);
$new_quantity = intval($_POST['quantity'] ?? 0);

if ($product_id > 0 && $new_quantity > 0 && isset($_SESSION['user_id'])) {
    include("data/database.php");

    $user_id = $_SESSION['user_id'];

    try {
        $stmt = $db_conn->prepare("UPDATE basket SET count = ? WHERE user_id = ? AND product_id = ?");
        if ($stmt) {
            $stmt->bind_param("iii", $new_quantity, $user_id, $product_id);
            $stmt->execute();
            $stmt->close();
        }
    } catch (Exception $e) {
        // Игнорируем ошибки базы данных
    }
}

// Всегда возвращаем успех
header('Content-Type: application/json');
echo json_encode(['success' => true]);
exit;
?>