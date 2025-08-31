<?php
$server_name = $_SERVER['SERVER_NAME'];
if ($server_name == 'localhost') {
    $host = "localhost";
    $login = "root";
    $password = "";
    $db_name = "kanskrop";
} else {
    $host = "localhost";
    $login = "u623724617_kanskrop";
    $password = "u6&HxJdZ+f6C";
    $db_name = "u623724617_kanskrop";
}

$db_conn = new mysqli($host, $login, $password, $db_name);
if ($db_conn->connect_error) {
    error_log("Database connection failed: " . $db_conn->connect_error);
    die("Database connection error");
}
$db_conn->set_charset("utf8");
?>