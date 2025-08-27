<?php
include('data/session_start.php');
include("data/database.php");
header('Content-Type: application/json');
$response = ['success' => false];
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $total_count = intval($_POST['total_count'] ?? 0);
    $_SESSION['cart_total_count'] = $total_count;
    $response['success'] = true;
} elseif (isset($_SESSION['cart'])) {
    $total_count = intval($_POST['total_count'] ?? 0);
    $_SESSION['cart_total_count'] = $total_count;
    $response['success'] = true;
}
echo json_encode($response);
exit;