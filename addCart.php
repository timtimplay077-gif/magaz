<?php
include('data/database.php');

// Если пользователь не авторизован - просим авторизоваться
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php?error=auth_required');
    exit;
}

$user_id = $_SESSION['user_id']; // Берем user_id из сессии
$product_id = $_GET["product_id"] ?? 0;

if ($product_id > 0) {
    // Проверяем есть ли товар уже в корзине ЭТОГО пользователя
    $check_sql = "SELECT * FROM basket WHERE user_id = '$user_id' AND product_id = $product_id";
    $result = $db_conn->query($check_sql);
    
    if ($result->num_rows > 0) {
        // Увеличиваем количество
        $row = $result->fetch_assoc();
        $new_count = $row['count'] + 1;
        $update_sql = "UPDATE basket SET count = '$new_count' WHERE id = " . $row['id'];
        $db_conn->query($update_sql);
    } else {
        // Добавляем новый товар
        $insert_sql = "INSERT INTO basket (user_id, product_id, count) VALUES ('$user_id', '$product_id', 1)";
        $db_conn->query($insert_sql);
    }
}

// Возвращаем обратно на страницу
header("Location: " . $_SERVER['HTTP_REFERER']);
exit;
?>