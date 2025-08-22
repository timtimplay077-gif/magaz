<?php
error_log("=== REMOVE FROM CART ===");
error_log("Session ID: " . session_id());
error_log("User ID: " . ($_SESSION['user_id'] ?? 'NOT SET'));
error_log("Product ID: " . ($_GET['product_id'] ?? 'NOT SET'));
include("data/database.php");
header('Content-Type: application/json');
if (isset($_SESSION['user_id']) && isset($_GET['product_id'])) {
    $product_id = intval($_GET['product_id']);
    $user_id = $_SESSION['user_id']; 
    error_log("Deleting: user_id=$user_id, product_id=$product_id");
    if ($product_id > 0) {
        try {
            $check_stmt = $db_conn->prepare("SELECT * FROM basket WHERE user_id = ? AND product_id = ?");
            $check_stmt->bind_param("ii", $user_id, $product_id);
            $check_stmt->execute();
            $result = $check_stmt->get_result();
            if ($result->num_rows > 0) {
                $delete_stmt = $db_conn->prepare("DELETE FROM basket WHERE user_id = ? AND product_id = ?");
                $delete_stmt->bind_param("ii", $user_id, $product_id);
                if ($delete_stmt->execute()) {
                    error_log("Successfully deleted from database");
                    $response = ['success' => true, 'deleted' => true];
                } else {
                    error_log("Delete failed: " . $delete_stmt->error);
                    $response = ['success' => true, 'deleted' => false];
                }
                $delete_stmt->close();
            } else {
                error_log("Item not found in basket");
                $response = ['success' => true, 'deleted' => false, 'message' => 'Товар не найден в корзине'];
            }
            $check_stmt->close();
            
        } catch (Exception $e) {
            error_log("Exception: " . $e->getMessage());
            $response = ['success' => true, 'deleted' => false];
        }
    } else {
        $response = ['success' => true, 'deleted' => false];
    }
} else {
    error_log("Not authorized or no product_id");
    $response = ['success' => true, 'deleted' => false];
}

echo json_encode($response);
exit;
?>