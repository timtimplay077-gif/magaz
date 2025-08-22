<?php
include("data/database.php");
header('Content-Type: application/json');

$product_id = intval($_GET['product_id'] ?? 0);
$response = ['success' => false, 'deleted' => false];

if ($product_id > 0) {
    if (isset($_SESSION['user_id'])) {
        // Для авторизованных - удаляем из БД
        $user_id = $_SESSION['user_id'];

        $delete_stmt = $db_conn->prepare("DELETE FROM basket WHERE user_id = ? AND product_id = ?");
        if ($delete_stmt) {
            $delete_stmt->bind_param("ii", $user_id, $product_id);
            if ($delete_stmt->execute()) {
                $response = ['success' => true, 'deleted' => true];
            }
            $delete_stmt->close();
        }
    } elseif (isset($_SESSION['cart'][$product_id])) {
        // Для неавторизованных - удаляем из сессии
        unset($_SESSION['cart'][$product_id]);
        $response = ['success' => true, 'deleted' => true];
    }
}

echo json_encode($response);
exit;
?>