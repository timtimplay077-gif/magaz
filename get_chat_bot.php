<?php
include('data/database.php');

$token = '7985968026:AAHoNcDbNimVpToWxoYlDskFoBajQ03T5Uc';
if (isset($_GET['orders'])) {
    $orders_sql = "SELECT o.*, u.first_name, u.last_name 
                   FROM orders o 
                   JOIN users u ON o.user_id = u.id 
                   ORDER BY o.created_at DESC 
                   LIMIT 10";
    $orders_result = $db_conn->query($orders_sql);
    
    $orders = [];
    if ($orders_result && $orders_result->num_rows > 0) {
        while ($order = $orders_result->fetch_assoc()) {
            $orders[] = $order;
        }
    }
    
    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode($orders);
    exit;
}
$response = file_get_contents("https://api.telegram.org/bot{$token}/getUpdates");
header('Content-Type: application/json; charset=UTF-8');
echo $response;
?>