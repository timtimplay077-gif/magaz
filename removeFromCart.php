<?php
include("data/database.php");

header('Content-Type: application/json');

// Получаем параметры
$product_id = $_GET['product_id'] ?? 0;

// Проверяем авторизацию через сессию
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'Не авторизован']);
    exit;
}

// Используем user_id из сессии для безопасности
$user_id = $_SESSION['user_id'];

if ($product_id > 0) {
    try {
        // Полностью удаляем товар из корзины
        $stmt = $db_conn->prepare("DELETE FROM basket WHERE user_id = ? AND product_id = ?");
        $stmt->bind_param("ii", $user_id, $product_id);
        
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'action' => 'deleted']);
        } else {
            echo json_encode(['success' => false, 'error' => 'Ошибка базы данных']);
        }
        
        $stmt->close();
        
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'error' => 'Ошибка базы данных: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Неверные параметры']);
}
exit;