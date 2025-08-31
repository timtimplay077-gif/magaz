<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include('data/database.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: login.php");
    exit();
}

$email = trim($_POST['email'] ?? '');
$password = trim($_POST['password'] ?? '');

if (empty($email) || empty($password)) {
    $_SESSION['login_error'] = "Будь ласка, заповніть всі поля.";
    header("Location: login.php");
    exit();
}

$stmt = $db_conn->prepare("SELECT * FROM users WHERE email = ?");
if (!$stmt) {
    $_SESSION['login_error'] = "Помилка сервера. Спробуйте пізніше.";
    header("Location: login.php");
    exit();
}

$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();

if ($result->num_rows === 0) {
    $_SESSION['login_error'] = "Такий e-mail не зареєстрований.";
    header("Location: login.php");
    exit();
}

$user = $result->fetch_assoc();

// УБИРАЕМ password_verify() - просто сравниваем пароли
if ($password !== $user['password']) {
    $_SESSION['login_error'] = "Невірний пароль.";
    header("Location: login.php");
    exit();
}

$_SESSION['user_id'] = $user['id'];
$_SESSION['email'] = $user['email'];
$_SESSION['firstName'] = $user['firstName'] ?? '';
$_SESSION['lastName'] = $user['lastName'] ?? '';
header("Location: index.php");
exit();
?>