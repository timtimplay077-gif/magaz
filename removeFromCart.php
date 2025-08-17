<?php
include("data/database.php");
$user_id = $_GET['user_id'] ?? 0;
$product_id = $_GET['product_id'] ?? 0;
if ($user_id > 0 && $product_id > 0) {
    $sql = "SELECT count FROM basket WHERE user_id = '$user_id' AND product_id = '$product_id'";
    $result = $db_conn->query($sql);

    if ($result && $row = $result->fetch_assoc()) {
        if ($row['count'] > 1) {
            $update_sql = "UPDATE basket SET count = count - 1 WHERE user_id = '$user_id' AND product_id = '$product_id'";
            $db_conn->query($update_sql);
        } else {
            $delete_sql = "DELETE FROM basket WHERE user_id = '$user_id' AND product_id = '$product_id'";
            $db_conn->query($delete_sql);
        }
    }
}

$redirect = $_SERVER['HTTP_REFERER'] ?? '/index.php';
header("Location: $redirect");
exit; ?>