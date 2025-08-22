<?php
// Включим отладку на время
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Начинаем сессию в самом начале
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Логируем информацию о сессии
error_log("=== ADD TO CART ===");
error_log("Session ID: " . session_id());
error_log("Session user_id: " . ($_SESSION['user_id'] ?? 'NOT SET'));
error_log("GET product_id: " . ($_GET['product_id'] ?? 'NOT SET'));

include('data/database.php');

$user_id = $_SESSION['user_id'];
$product_id = intval($_GET["product_id"] ?? 0);

error_log("Processing: user_id=$user_id, product_id=$product_id");

if ($product_id > 0) {
    try {
        // Проверяем существует ли товар
        $check_product = $db_conn->prepare("SELECT id FROM products WHERE id = ?");
        $check_product->bind_param("i", $product_id);
        $check_product->execute();
        $product_exists = $check_product->get_result()->num_rows > 0;
        $check_product->close();

        if (!$product_exists) {
            error_log("Product $product_id does not exist");
            header("Location: " . $_SERVER['HTTP_REFERER']);
            exit;
        }

        // Проверяем есть ли товар в корзине
        $check_sql = "SELECT * FROM basket WHERE user_id = ? AND product_id = ?";
        $stmt = $db_conn->prepare($check_sql);

        if (!$stmt) {
            throw new Exception("Prepare failed: " . $db_conn->error);
        }

        $stmt->bind_param("ii", $user_id, $product_id);

        if (!$stmt->execute()) {
            throw new Exception("Execute failed: " . $stmt->error);
        }

        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Увеличиваем количество
            $row = $result->fetch_assoc();
            $new_count = $row['count'] + 1;

            $update_sql = "UPDATE basket SET count = ? WHERE id = ?";
            $update_stmt = $db_conn->prepare($update_sql);

            if (!$update_stmt) {
                throw new Exception("Prepare update failed: " . $db_conn->error);
            }

            $update_stmt->bind_param("ii", $new_count, $row['id']);

            if (!$update_stmt->execute()) {
                throw new Exception("Update execute failed: " . $update_stmt->error);
            }

            $update_stmt->close();
            error_log("Updated product $product_id to count $new_count");

        } else {
            // Добавляем новый товар
            $insert_sql = "INSERT INTO basket (user_id, product_id, count) VALUES (?, ?, 1)";
            $insert_stmt = $db_conn->prepare($insert_sql);

            if (!$insert_stmt) {
                throw new Exception("Prepare insert failed: " . $db_conn->error);
            }

            $insert_stmt->bind_param("ii", $user_id, $product_id);

            if (!$insert_stmt->execute()) {
                throw new Exception("Insert execute failed: " . $insert_stmt->error);
            }

            $insert_stmt->close();
            error_log("Added new product $product_id to basket");
        }

        $stmt->close();

    } catch (Exception $e) {
        error_log("Error in addCart: " . $e->getMessage());
        // Продолжаем выполнение, даже если есть ошибка
    }
} else {
    error_log("Invalid product ID: $product_id");
}

header("Location: " . $_SERVER['HTTP_REFERER']);
exit;
?>