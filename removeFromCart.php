<?php
include("data/database.php");

$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    http_response_code(403);
    echo "Нет доступа";
    exit;
}

if (isset($_POST['id'])) {
    $product_id = intval($_POST['id']);
    $sql = "DELETE FROM basket WHERE user_id = ? AND product_id = ?";
    $stmt = $db_conn->prepare($sql);
    $stmt->bind_param("ii", $user_id, $product_id);
    $stmt->execute();

    echo "OK";
} else {
    echo "NO ID";
}