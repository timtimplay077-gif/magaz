<?php
include('data/session_start.php');
include("data/database.php");
header('Content-Type: application/json');

$product_id = intval($_POST['product_id'] ?? 0);
$new_quantity = intval($_POST['quantity'] ?? 0);

$response = [
    'success' => false,
    'new_quantity' => 0,
    'cart_count' => 0,
    'cart_total' => 0.0
];

if ($product_id > 0 && $new_quantity > 0) {
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
        $stmt = $db_conn->prepare("UPDATE basket SET count = ? WHERE user_id = ? AND product_id = ?");
        if ($stmt) {
            $stmt->bind_param("iii", $new_quantity, $user_id, $product_id);
            if ($stmt->execute()) {
                $response['success'] = true;
                $response['new_quantity'] = $new_quantity;
            }
            $stmt->close();
        }
        $stmt = $db_conn->prepare("SELECT SUM(count) as total_count FROM basket WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        $response['cart_count'] = (int) ($result['total_count'] ?? 0);
        $stmt->close();

        $stmt = $db_conn->prepare("
            SELECT SUM(b.count * p.price) as total_sum 
            FROM basket b 
            JOIN products p ON b.product_id = p.id 
            WHERE b.user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        $response['cart_total'] = (float) ($result['total_sum'] ?? 0);
        $stmt->close();

    } elseif (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] = $new_quantity;
        $response['success'] = true;
        $response['new_quantity'] = $new_quantity;
        $response['cart_count'] = array_sum($_SESSION['cart']);
    }
}

echo json_encode($response);
exit;
