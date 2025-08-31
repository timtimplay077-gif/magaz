<?php
$server_name = $_SERVER['SERVER_NAME'];
if ($server_name == 'localhost') {
    $host = "localhost";
    $login = "root";
    $password = "";
    $db_name = "kanskrop";
} else {
    $host = "localhost";
    $login = "u623724617_kanskrop";
    $password = "u6&HxJdZ+f6C";
    $db_name = "u623724617_kanskrop";
}

$db_conn = new mysqli($host, $login, $password, $db_name);
if ($db_conn->connect_error) {
    error_log("Database connection failed: " . $db_conn->connect_error);
    die("Database connection error");
}
$db_conn->set_charset("utf8");
if (isset($_SESSION['user_id']) && $_SESSION['user_id'] > 0) {
    $user_id = $_SESSION['user_id'];
    $check_sale_sql = "SELECT sale FROM users WHERE id = ?";
    $check_stmt = $db_conn->prepare($check_sale_sql);
    $check_stmt->bind_param("i", $user_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result && $check_result->num_rows > 0) {
        $user_data = $check_result->fetch_assoc();
        if ($user_data['sale'] != 10) {
            $update_sale_sql = "UPDATE users SET sale = 10 WHERE id = ?";
            $update_stmt = $db_conn->prepare($update_sale_sql);
            $update_stmt->bind_param("i", $user_id);
            $update_stmt->execute();
            $update_stmt->close();
        }
    }
    $check_stmt->close();
}
?>