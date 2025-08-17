<?php
include("data/database.php");
$user_id = $_GET['user_id'] ?? 0;
$product_id = $_GET['product_id'] ?? 0;

if ($user_id > 0 && $product_id > 0) {

    // Prepared statement для выборки count
    $stmt = $db_conn->prepare("SELECT count FROM basket WHERE user_id = ? AND product_id = ?");
    $stmt->bind_param("ii", $user_id, $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        if ($row['count'] > 1) {
            $stmt2 = $db_conn->prepare("UPDATE basket SET count = count - 1 WHERE user_id = ? AND product_id = ?");
            $stmt2->bind_param("ii", $user_id, $product_id);
            $stmt2->execute();
        } else {
            $stmt2 = $db_conn->prepare("DELETE FROM basket WHERE user_id = ? AND product_id = ?");
            $stmt2->bind_param("ii", $user_id, $product_id);
            $stmt2->execute();
        }
    }
}

$redirect = $_SERVER['HTTP_REFERER'] ?? '/index.php';
header("Location: $redirect");
exit;