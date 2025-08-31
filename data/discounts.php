<?php
if (!function_exists('calculateFinalPrice')) {
    function calculateFinalPrice($productPrice, $productModifier = 0, $userSale = 0)
    {
        $price = $productPrice * (1 + $productModifier / 100);

        if ($userSale > 0) {
            $price = $price * (1 - $userSale / 100);
        }

        return round($price, 2);
    }
}

if (!function_exists('getUserSale')) {
    function getUserSale($userId, $db_conn)
    {
        $sql = "SELECT sale FROM users WHERE id = ?";
        $stmt = $db_conn->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            $user = $result->fetch_assoc();
            return $user['sale'] ?? 0;
        }

        return 0;
    }
}
?>