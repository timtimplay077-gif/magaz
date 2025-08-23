<?php
// Включим максимальную отладку
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include('data/database.php');

echo "<h2>ТЕСТ ВХОДА</h2>";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    echo "<p>Пытаемся войти с: Email=$email, Password=$password</p>";
    
    // Простая проверка
    $stmt = $db_conn->prepare("SELECT * FROM users WHERE email = ?");
    if ($stmt) {
        $stmt->bind_param("s", $email);
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            
            if ($result->num_rows === 0) {
                echo "<p style='color: red;'>ОШИБКА: Пользователь с email $email не найден</p>";
            } else {
                $user = $result->fetch_assoc();
                echo "<p>Найден пользователь: " . print_r($user, true) . "</p>";
                
                if ($user['password'] === $password) {
                    $_SESSION['user_id'] = $user['id'];
                    echo "<p style='color: green;'>УСПЕХ: Пароль верный! User ID = " . $_SESSION['user_id'] . "</p>";
                    echo "<p><a href='test_auth.php'>Вернуться к тесту</a></p>";
                } else {
                    echo "<p style='color: red;'>ОШИБКА: Неверный пароль! Ожидалось: " . $user['password'] . ", получено: $password</p>";
                }
            }
        } else {
            echo "<p style='color: red;'>ОШИБКА выполнения запроса: " . $stmt->error . "</p>";
        }
        $stmt->close();
    } else {
        echo "<p style='color: red;'>ОШИБКА подготовки запроса: " . $db_conn->error . "</p>";
    }
} else {
    echo "<p>Форма не отправлена</p>";
}

echo "<p><a href='test_auth.php'>Вернуться к тесту</a></p>";
?>