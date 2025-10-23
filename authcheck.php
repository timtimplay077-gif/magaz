<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include('data/database.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: login.php");
    exit();
}

$login = trim($_POST['login'] ?? '');
$password = trim($_POST['password'] ?? '');

if (empty($login) || empty($password)) {
    $_SESSION['login_error'] = "Будь ласка, заповніть всі поля.";
    header("Location: login.php");
    exit();
}

if (filter_var($login, FILTER_VALIDATE_EMAIL)) {
    $stmt = $db_conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $login);
} else {
    $phone = $login;
    if (strpos($phone, '+380') === false) {
        $phone = '+380' . preg_replace('/^38?0?/', '', $phone);
    }

    $stmt = $db_conn->prepare("SELECT * FROM users WHERE phone = ?");
    $stmt->bind_param("s", $phone);
}

if (!$stmt) {
    $_SESSION['login_error'] = "Помилка сервера. Спробуйте пізніше.";
    header("Location: login.php");
    exit();
}

$stmt->execute();
$result = $stmt->get_result();
$stmt->close();

if ($result->num_rows === 0) {
    $_SESSION['login_error'] = "Такий номер телефону не зареєстрований.";
    header("Location: login.php");
    exit();
}

$user = $result->fetch_assoc();
if ($password !== $user['password']) {
    $_SESSION['login_error'] = "Невірний пароль.";
    header("Location: login.php");
    exit();
}

$_SESSION['user_id'] = $user['id'];
$_SESSION['email'] = $user['email'];
$_SESSION['phone'] = $user['phone'];
$_SESSION['firstName'] = $user['firstName'] ?? '';
$_SESSION['lastName'] = $user['lastName'] ?? '';
header("Location: mainpage.php");
exit();
?>