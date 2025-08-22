<?php
include('data/database.php');
$response = ['status' => 'error', 'message' => '', 'cart_count' => 0];
if (!isset($_SESSION['user_id'])) {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    $product_id = intval($_GET["product_id"] ?? 0);

    if ($product_id > 0) {
        if (isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id]++;
        } else {
            $_SESSION['cart'][$product_id] = 1;
        }

        $response = [
            'status' => 'success',
            'message' => 'added_to_session',
            'cart_count' => array_sum($_SESSION['cart'])
        ];
    }
} else {
    $user_id = $_SESSION['user_id'];
    $product_id = intval($_GET["product_id"] ?? 0);

    if ($product_id > 0) {
        try {
            $check_product = $db_conn->prepare("SELECT id FROM products WHERE id = ?");
            $check_product->bind_param("i", $product_id);
            $check_product->execute();
            $product_exists = $check_product->get_result()->num_rows > 0;
            $check_product->close();

            if (!$product_exists) {
                $response = ['status' => 'error', 'message' => 'product_not_found'];
            } else {
                $check_sql = "SELECT * FROM basket WHERE user_id = ? AND product_id = ?";
                $stmt = $db_conn->prepare($check_sql);
                $stmt->bind_param("ii", $user_id, $product_id);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    $update_sql = "UPDATE basket SET count = count + 1 WHERE user_id = ? AND product_id = ?";
                    $update_stmt = $db_conn->prepare($update_sql);
                    $update_stmt->bind_param("ii", $user_id, $product_id);

                    if ($update_stmt->execute()) {
                        $response['status'] = 'success';
                        $response['message'] = 'updated_in_db';
                    } else {
                        $response['message'] = 'update_failed';
                    }
                    $update_stmt->close();
                } else {
                    $insert_sql = "INSERT INTO basket (user_id, product_id, count) VALUES (?, ?, 1)";
                    $insert_stmt = $db_conn->prepare($insert_sql);
                    $insert_stmt->bind_param("ii", $user_id, $product_id);

                    if ($insert_stmt->execute()) {
                        $response['status'] = 'success';
                        $response['message'] = 'added_to_db';
                    } else {
                        $response['message'] = 'insert_failed';
                    }
                    $insert_stmt->close();
                }
                $stmt->close();
                $count_sql = "SELECT SUM(count) as total FROM basket WHERE user_id = ?";
                $count_stmt = $db_conn->prepare($count_sql);
                $count_stmt->bind_param("i", $user_id);
                $count_stmt->execute();
                $count_result = $count_stmt->get_result();
                $count_row = $count_result->fetch_assoc();

                $response['cart_count'] = $count_row['total'] ?? 0;
                $count_stmt->close();
            }
        } catch (Exception $e) {
            error_log("Error in addCart: " . $e->getMessage());
            $response['message'] = 'database_error';
        }
    }
}

header('Content-Type: application/json');
echo json_encode($response);
exit;
?>