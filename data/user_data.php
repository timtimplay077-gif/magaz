<?php
$user_id = $_SESSION['user_id'] ?? $_SESSION['id'] ?? 0;
$user_sql = "SELECT * FROM users WHERE id = '$user_id'";
$user_query = $db_conn->query($user_sql);
$user_row = $user_query->fetch_assoc();
?>