<?php
include('data/database.php');

// Простая проверка авторизации
$is_logged_in = isset($_SESSION['user_id']);

if ($is_logged_in) {
    $user_id = $_SESSION['user_id'];
    $basket_sql = "SELECT b.*, p.*, b.count as basket_count 
                   FROM basket b 
                   JOIN products p ON b.product_id = p.id 
                   WHERE b.user_id = '$user_id'";
    $basket_query = $db_conn->query($basket_sql);
} else {
    $basket_query = false;
}

if ($basket_query && $basket_query->num_rows > 0) {
    while ($item = $basket_query->fetch_assoc()) {
        $item_total = $item['price'] * $item['basket_count'];
        ?>
        <div class="header_card_product" data-id="<?= $item['id'] ?>" data-price="<?= $item['price'] ?>">
            <div class="delete-wrapper">
                <a href="#" class="delete-btn" onclick="removeFromCart(<?= $item['id'] ?>)">
                    <img src="img/recycle-bin.png" alt="Видалити">
                </a>
            </div>
            <div class="photo-wrapper">
                <img src="<?= $item['img'] ?>" alt="<?= $item['name'] ?>">
            </div>
            <div class="name-wrapper">
                <p><?= $item['name'] ?></p>
            </div>
            <div class="price-wrapper">
                <span class="price"><?= number_format($item_total, 2) ?> ₴</span>
            </div>
            <div class="quantity-wrapper">
                <button class="qty-btn minus" onclick="changeQuantity(this, 'decrease')">-</button>
                <span class="count"><?= $item['basket_count'] ?></span>
                <button class="qty-btn plus" onclick="changeQuantity(this, 'increase')">+</button>
            </div>
        </div>
        <?php
    }
} else {
    echo '<p class="empty-cart">Кошик порожній</p>';
}
?>