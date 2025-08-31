<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$user_id = $_SESSION['user_id'] ?? $_SESSION['id'] ?? 0;
$user_row = null;
$user_query = new mysqli_result($db_conn, 0);
$is_logged_in = false;

if ($user_id > 0) {
    $stmt = $db_conn->prepare("SELECT * FROM users WHERE id = ?");
    if ($stmt) {
        $stmt->bind_param("i", $user_id);
        if ($stmt->execute()) {
            $user_query = $stmt->get_result();
            $user_row = $user_query->fetch_assoc();
            $is_logged_in = ($user_query->num_rows > 0);
            if ($is_logged_in && $user_row['sale'] != 10) {
                $update_stmt = $db_conn->prepare("UPDATE users SET sale = 10 WHERE id = ?");
                $update_stmt->bind_param("i", $user_id);
                $update_stmt->execute();
                $update_stmt->close();
                $user_row['sale'] = 10;
            }
        }
        $stmt->close();
    }
}

error_log("User ID: $user_id, Logged in: " . ($is_logged_in ? 'YES' : 'NO') . ", Sale: " . ($user_row['sale'] ?? 0) . "%");
?>