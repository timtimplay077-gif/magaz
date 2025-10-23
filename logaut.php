<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include('data/database.php');
$_SESSION = array();
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}
session_destroy();
session_start();
$_SESSION['logout_success'] = 'Ви успішно вийшли з системи';
header("Location: mainpage.php");
exit();
?>