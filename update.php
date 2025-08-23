<?php
include('data/session_start.php');
include("data/database.php");
header('Content-Type: application/json');

$product_id = intval($_POST['product_id'] ?? 0);
$new_quantity = intval($_POST['quantity'] ?? 0);
$response = ['success' => false];

if ($product_id > 0 && $new_quantity > 0) {
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];

        $stmt = $db_conn->prepare("UPDATE basket SET count = ? WHERE user_id = ? AND product_id = ?");
        if ($stmt) {
            $stmt->bind_param("iii", $new_quantity, $user_id, $product_id);
            if ($stmt->execute()) {
                $response = ['success' => true];
            }
            $stmt->close();
        }
    } elseif (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] = $new_quantity;
        $response = ['success' => true];
    }
}

echo json_encode($response);
exit;
?>