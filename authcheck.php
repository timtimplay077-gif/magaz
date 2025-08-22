<?php
include('data/database.php');
$email = $_GET['email'];
$password = $_GET['password'];
$stmt = $db_conn->prepare("SELECT * FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
if (!$row) {
    $_SESSION['login_error'] = "Такий e-mail не зареєстрований.";
    header("Location: login.php");
    exit();
} else {
    if ($row['password'] !== $password) {
        $_SESSION['login_error'] = "Невірний пароль.";
        header("Location: login.php");
        exit();
    } else {
        $_SESSION['id'] = $row['id'];
        $_SESSION['user_id'] = $row['id']; 
        header("Location: index.php");
        exit();
    }
}
?>