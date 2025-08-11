<?php
$basket_sql = "SELECT * FROM basket WHERE user_id = '$user_id'";
$basket_query = $db_conn->query($basket_sql);
$basket_product_id = [];
for ($i = 0; $i < $basket_query->num_rows; $i++) {
    $basket_row = $basket_query->fetch_assoc();
    $basket_product_id[] = $basket_row['product_id'];
}
$in = implode(',', array_map('intval', $basket_product_id));
$basket_product = "SELECT * FROM products WHERE id IN ($in)";
$basket_product_query = $db_conn->query($basket_product);
?>
<script src="js/main.js"></script>
<div class="overlay" id="overlay" onclick="closeCart()"></div>
<div class="modal modal-basket" id="cartModal">
    <div class="cart-title">Кошик</div>
    <hr>

    <div id="cart-items">
        <?php while ($row = $basket_product_query->fetch_assoc()) { ?>
            <div class="header_card_product" data-id="<?php echo $row['id']; ?>" data-price="<?php echo $row['price']; ?>">
                <div class="delete-wrapper">
                    <button class="delete-btn">🗑</button>
                </div>
                <div class="photo-wrapper">
                    <img src="<?php echo $row['img']; ?>" alt="">
                </div>
                <div class="name-wrapper">
                    <?php echo $row['name']; ?>
                </div>
                <div class="price-wrapper">
                    <span class="price"><?php echo $row['price']; ?></span>⠀<p>₴</p>
                </div>
                <div class="quantity-wrapper">
                    <button type="button" class="qty-btn minus">−</button>
                    <span class="count">1</span>
                    <button type="button" class="qty-btn plus">+</button>
                </div>

            </div>
            <hr>
        <?php } ?>
    </div>

    <div class="cart-footer">
        <span id="cart-count">В кошику: 0 товарів</span>
        <span id="cart-total">на суму: 0 ₴</span>
    </div>


    <a href="checkout.php" class="buy-button">Оформити замовлення</a>
</div>