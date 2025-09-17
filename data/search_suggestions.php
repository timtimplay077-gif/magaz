<?php
include('database.php');
header('Content-Type: application/json');

if (!isset($_GET['q']) || empty(trim($_GET['q']))) {
    echo json_encode([]);
    exit;
}

$query = trim($_GET['q']);
$search_term = $db_conn->real_escape_string($query);
$sql = "SELECT name FROM products 
        WHERE name LIKE ? 
           OR name LIKE ?
        GROUP BY name 
        ORDER BY 
            CASE 
                WHEN name LIKE ? THEN 1
                WHEN name LIKE ? THEN 2
                ELSE 3
            END,
            name ASC
        LIMIT 8";

$stmt = $db_conn->prepare($sql);
$start_with = $query . '%';
$contains = '%' . $query . '%';
$stmt->bind_param("ssss", $start_with, $contains, $start_with, $contains);
$stmt->execute();
$result = $stmt->get_result();

$suggestions = [];
while ($row = $result->fetch_assoc()) {
    $suggestions[] = $row['name'];
}

echo json_encode($suggestions);
?>