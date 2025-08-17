<?php
if (!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = 1;
}
$user_id = $_SESSION['user_id'];
$basket_sql = "SELECT * FROM basket WHERE user_id = '$user_id'";
$basket_query = $db_conn->query($basket_sql);
$basket_product_id = [];
while ($basket_row = $basket_query->fetch_assoc()) {
    $basket_product_id[$basket_row['product_id']] = $basket_row['count'];
}
$basket_product_query = null;
if (!empty($basket_product_id)) {
    $in = implode(',', array_map('intval', array_keys($basket_product_id)));
    $basket_product_sql = "SELECT * FROM products WHERE id IN ($in)";
    $basket_product_query = $db_conn->query($basket_product_sql);
}
$user_row = $db_conn->query("SELECT sale FROM users WHERE id = '$user_id'")->fetch_assoc();
?>
<script src="js/main.js"></script>
<div class="modal modal-basket" id="cartModal">
    <div class="cart-header">
        <div class="flex_close">
            <div class="cart-title">
                <p>Кошик</p>
            </div>
            <button class="delete-button" onclick="closeCart()">
                <img src="img/close.png" alt="Закрыть">
            </button>
        </div>

    </div>
    <div id="cart-items">
        <?php if (!empty($basket_product_query) && $basket_product_query->num_rows > 0) { ?>
            <?php while ($row = $basket_product_query->fetch_assoc()) {
                $original_price = $row['price'];
                if (isset($user_row['sale']) && $user_row['sale'] > 0) {
                    $final_price = $original_price * (1 - $user_row['sale'] / 100);
                } else {
                    $final_price = $original_price;
                }
                $count = $basket_product_id[$row['id']];
                ?>
                <div class="header_card_product" data-id="<?php echo $row['id']; ?>" data-price="<?php echo $final_price; ?>">
                    <div class="delete-wrapper">
                        <a href="removeFromCart.php?user_id=1&amp;product_id=222" class="delete-btn">🗑</a>
                    </div>
                    <div class="photo-wrapper">
                        <img src="<?php echo $row['img']; ?>" alt="">
                    </div>
                    <div class="name-wrapper">
                        <p><?php echo $row['name']; ?></p>
                    </div>
                    <div class="price-wrapper">
                        <span class="price"><?php echo $final_price; ?> ₴</span>
                    </div>
                    <div class="quantity-wrapper">
                        <button type="button" class="qty-btn minus">-</button>
                        <span class="count"><?php echo $count; ?></span>
                        <button type="button" class="qty-btn plus">+</button>
                    </div>
                </div>
            <?php } ?>
        <?php } ?>
    </div>
    <div class="cart-footer">
        <span id="cart-count">В кошику: 0 товарів</span>
        <span id="cart-total">на суму: 0 ₴</span>
    </div>


    <a href="chekout.php" class="buy-button">Оформити замовлення</a>
</div>