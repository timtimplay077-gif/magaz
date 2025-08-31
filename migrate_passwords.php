<?php
include('data/database.php');

$sql = "SELECT id, password FROM users WHERE password NOT LIKE '$2y$%'";
$result = $db_conn->query($sql);

while ($user = $result->fetch_assoc()) {
    $hashed_password = password_hash($user['password'], PASSWORD_DEFAULT);
    $update_sql = "UPDATE users SET password = ? WHERE id = ?";
    $stmt = $db_conn->prepare($update_sql);
    $stmt->bind_param("si", $hashed_password, $user['id']);
    $stmt->execute();
    $stmt->close();
    
    echo "Updated password for user ID: " . $user['id'] . "<br>";
}

echo "Migration completed!";
?>