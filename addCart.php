<?php
include('data/database.php');
$errors = [];
$user_id = $_GET["user_id"] ?? 0;
$product_id = $_GET["product_id"] ?? 0;
// if ($confirmPassword !== $password) {
//     $errors["password"] = true;
// }
// $db_sql_email = "SELECT * FROM users WHERE email = '$email'";

// $tabl = $db_conn->query($db_sql_email);
// $email_row = $tabl->fetch_assoc();
// if ($email_row) {
//     $errors["email"] = true;
// }
$db_cart_sql = "SELECT * FROM basket WHERE user_id = '$user_id' AND product_id = $product_id";
$db_cart_query = $db_conn->query($db_cart_sql);
// 
if ($db_cart_query->num_rows > 0) {
    $db_cart_row = $db_cart_query->fetch_assoc();
    $cart_id = $db_cart_row['id'];
    $count = $db_cart_row['count'] + 1;
    // $db_cart_sql = "SELECT * FROM basket WHERE user_id = '$user_id' AND product_id = $product_id";
    $db_cart_sql = "UPDATE `basket` SET `count` = '$count' WHERE `basket`.`id` = $cart_id;";
    $db_cart_query = $db_conn->query($db_cart_sql);

} else {
    $add_cart_sql = "INSERT INTO `basket` (`id`, `user_id`, `product_id`, `count`) VALUES (NULL, '$user_id', '$product_id', '1')";
    $add_cart_query = $db_conn->query($add_cart_sql);
    
}
header("Location: " . $_SERVER['HTTP_REFERER']);

