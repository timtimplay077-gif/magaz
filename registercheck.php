<?php
include('data/database.php');
include('data/session_start.php');
$errors = [];
$_SESSION["get"] = $_POST;
$firstName = $_POST["firstName"];
$lastName = $_POST["lastName"];
$email = $_POST["email"];
$phone = $_POST['phone'];
$password = $_POST["password"]; // Оставляем как есть, без хэширования
$confirmPassword = $_POST['confirmPassword'];

if ($confirmPassword !== $password) {
    $errors["password"] = true;
}

$db_sql_email = "SELECT * FROM users WHERE email = ?";
$stmt = $db_conn->prepare($db_sql_email);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$email_row = $result->fetch_assoc();
$stmt->close();

if ($email_row) {
    $errors["email"] = true;
}

if (strlen($firstName) < 1 || strlen($firstName) > 32) {
    $errors["firstName"] = true;
}

if (strlen($lastName) < 1 || strlen($lastName) > 32) {
    $errors["lastName"] = true;
}

// Исправляем проверку телефона
$phone = $_POST['phone'];

// Убедимся что телефон начинается с +380
if (!str_starts_with($phone, '+380')) {
    // Добавляем +380 если его нет
    $cleanPhone = preg_replace('/[^0-9]/', '', $phone);
    if (str_starts_with($cleanPhone, '380')) {
        $phone = '+' . $cleanPhone;
    } else {
        $phone = '+380' . $cleanPhone;
    }
}

// Проверяем длину (+380 = 4 символа + 9 цифр = 13 символов)
if (strlen($phone) !== 13 || !preg_match('/^\+380\d{9}$/', $phone)) {
    $errors["phone"] = true;
}

// Также проверяем уникальность телефона
$db_sql_phone = "SELECT * FROM users WHERE phone = ?";
$stmt = $db_conn->prepare($db_sql_phone);
$stmt->bind_param("s", $phone);
$stmt->execute();
$result = $stmt->get_result();
$phone_row = $result->fetch_assoc();
$stmt->close();

if ($phone_row) {
    $errors["phone"] = true;
}
if (count($errors)) {
    $_SESSION["errors"] = $errors;
    header("Location: registration.php");
    exit;
} else {
    // УБИРАЕМ ХЭШИРОВАНИЕ - сохраняем пароль как есть
    $register_sql = "INSERT INTO `users` (`firstName`, `lastName`, `email`, `phone`, `password`, `sale`) 
                     VALUES (?, ?, ?, ?, ?, '0')";
    $stmt = $db_conn->prepare($register_sql);
    $stmt->bind_param("sssss", $firstName, $lastName, $email, $phone, $password); // Пароль без хэша

    if ($stmt->execute()) {
        $stmt->close();
        unset($_SESSION["get"]);
        unset($_SESSION["errors"]);
        header("Location: accountCreate.php");
        exit;
    } else {
        $_SESSION["errors"]["database"] = true;
        header("Location: registration.php");
        exit;
    }
}
?>