<?php
include('data/database.php');
$email = $_GET["email"];
$password = $_GET["password"];
$db_sql = "SELECT * FROM users WHERE email = '$email' AND password ='$password'";
$tabl = $db_conn->query($db_sql);
$row = $tabl->fetch_assoc();
if ($row == null) {
    print_r("Такого юзера не існоє");
    header("Location: index.php");
} else {
    print_r("Ви авторизувались");
    print_r($row['id']);
    $_SESSION['id'] = $row['id'];
}
header("Location: index.php");






