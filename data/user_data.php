<?php
$user_id = $_SESSION['user_id'] ?? $_SESSION['id'] ?? 0;
$stmt = $db_conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user_row = $result->fetch_assoc();
$user_query = $result; 
?>