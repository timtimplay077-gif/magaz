<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include('data/database.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: login.php");
    exit();
}

// Получаем данные из формы
$email = trim($_POST['email'] ?? '');
$password = trim($_POST['password'] ?? '');

if (empty($email) || empty($password)) {
    $_SESSION['login_error'] = "Будь ласка, заповніть всі поля.";
    header("Location: login.php");
    exit();
}

// Подготовка запроса
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

// Проверяем существование пользователя
if ($result->num_rows === 0) {
    $_SESSION['login_error'] = "Такий e-mail не зареєстрований.";
    header("Location: login.php");
    exit();
}

$user = $result->fetch_assoc();

// Проверка пароля
// Если в базе plain text:
if ($user['password'] !== $password) {
    $_SESSION['login_error'] = "Невірний пароль.";
    header("Location: login.php");
    exit();
}

/*
// Если в базе хешированные пароли, раскомментируй это и закомментируй предыдущую проверку
if (!password_verify($password, $user['password'])) {
    $_SESSION['login_error'] = "Невірний пароль.";
    header("Location: login.php");
    exit();
}
*/

// Сохраняем данные в сессии
$_SESSION['user_id'] = $user['id'];
$_SESSION['email'] = $user['email'];
$_SESSION['firstName'] = $user['firstName'] ?? '';
$_SESSION['lastName'] = $user['lastName'] ?? '';

// Редирект на главную
header("Location: index.php");
exit();
?>