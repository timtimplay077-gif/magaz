<?php
session_start();
// $host = "localhost";
// $login = "root";
// $password = "";
// $db_name = "kanskrop";
// $host = "localhost";
// $login = "u623724617_kanskrop";
// $password = "u6&HxJdZ+f6C";
// $db_name = "u623724617_kanskrop";
// u6&HxJdZ+f6C
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



?>
123123hghghghg