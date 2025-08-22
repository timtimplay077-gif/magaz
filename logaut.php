<?php
// Начинаем сессию
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Подключаем базу данных
include('data/database.php');

// Полностью очищаем все данные сессии
$_SESSION = array();

// Если используются cookies сессии, удаляем их
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

// Уничтожаем сессию
session_destroy();

// Начинаем новую сессию для возможных сообщений
session_start();

// Устанавливаем сообщение об успешном выходе
$_SESSION['logout_success'] = 'Ви успішно вийшли з системи';

// Перенаправляем на главную страницу
header("Location: index.php");
exit();
?>