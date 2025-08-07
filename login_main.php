<?php
include('data/database.php');
$email = $_GET['email'];
$password = $_GET['password'];
$db_sql = "SELECT * FROM users WHERE email = '$email'";
$tabl = $db_conn->query($db_sql);
$row = $tabl->fetch_assoc();

if (!$row) {
    $_SESSION['login_error'] = "Такий e-mail не зареєстрований.";
    header("Location: index.php");
    exit();
} else {
    if ($row['password'] !== $password) {
        $_SESSION['login_error'] = "Невірний пароль.";
        header("Location:  index.php");
        exit();
    } else {
        $_SESSION['id'] = $row['id'];
        header("Location:  index.php");
        exit();
    }
}
?>