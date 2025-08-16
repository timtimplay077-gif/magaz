<?php
include("data/database.php");

$user_id = $_GET['user_id'] ?? 0;
$product_id = $_GET['product_id'] ?? 0;

if ($user_id > 0 && $product_id > 0) {
    // Удаляем товар только для этого пользователя
    $delete_sql = "DELETE FROM basket WHERE user_id = '$user_id' AND product_id = '$product_id'";
    $db_conn->query($delete_sql);
}

// Возвращаем обратно на страницу (или на корзину)
$redirect = $_SERVER['HTTP_REFERER'] ?? '/index.php';
header("Location: $redirect");
exit;