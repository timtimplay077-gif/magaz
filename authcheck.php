<?php
include('data/database.php');
// $login_error = [];
// $email = $_GET["email"];
// $password = $_GET["password"];
// $db_sql = "SELECT * FROM users WHERE email = '$email' AND password ='$password'";
// $tabl = $db_conn->query($db_sql);
// $row = $tabl->fetch_assoc();
// if ($row == null) {
//     $login_error["password"] = true;
//     header("Location: login.php");
// } else {
//     print_r("Ви авторизувались");
//     print_r($row['id']);
//     $_SESSION['id'] = $row['id'];
//     header("Location: index.php");
// }
$email = $_GET['email'];
$password = $_GET['password'];
$db_sql = "SELECT * FROM users WHERE email = '$email'";
$tabl = $db_conn->query($db_sql);
$row = $tabl->fetch_assoc();

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
        header("Location: index.php");
        exit();
    }
}






