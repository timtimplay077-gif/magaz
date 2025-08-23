<?php
include('data/database.php');
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
$db_sql_email = "SELECT * FROM users WHERE email = '$email'";
$tabl = $db_conn->query($db_sql_email);
$email_row = $tabl->fetch_assoc();
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
    print_r($errors);
    $_SESSION["errors"] = $errors;
    header("Location: registration.php");
} else {
    header("Location: accountCreate.php");
    $register_sql = "INSERT INTO `users` (`id`, `firstName`, `lastName`, `email`, `phone`, `password`, `sale`) 
                 VALUES (NULL, '$firstName', '$lastName', '$email', '$phone', '$password', '0')";
    $register_query = $db_conn->query($register_sql);
}

