<?php
include('data/database.php');
include('data/session_start.php');
$errors = [];
$_SESSION["get"] = $_GET;
$firstName = $_GET["firstName"];
$lastName = $_GET["lastName"];
$email = $_GET["email"];
$phone = $_GET['phone'];
$password = $_GET["password"];
$confirmPassword = $_GET['confirmPassword'];
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
if (strlen($phone) < 9 || strlen($phone) > 12) {
    $errors["phone"] = true;
}
if (count($errors)) {
    $_SESSION["errors"] = $errors;
    header("Location: registration.php");
    exit;
} else {
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $register_sql = "INSERT INTO `users` (`firstName`, `lastName`, `email`, `phone`, `password`, `sale`) 
                     VALUES (?, ?, ?, ?, ?, '0')";
    $stmt = $db_conn->prepare($register_sql);
    $stmt->bind_param("sssss", $firstName, $lastName, $email, $phone, $hashedPassword);

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