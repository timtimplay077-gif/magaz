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
    <div class="modal-header">
        <p>Кошик</p>
        <span class="close-btn" onclick="closeCart()">×</span>
    </div>
    <hr>
    <?php
    for ($i = 0; $i < $basket_product_query->num_rows; $i++) {
        $row = $basket_product_query->fetch_assoc();
        ?>
        <div class="header_card_product">
            <img class="basket_pdoruct_photo" src="<?php print_r($row["img"]) ?>" alt="">
            <p><?php print_r($row["name"]) ?></p>
            <p class="price_basket">₴<?php print_r($row["price"]) ?></p>
        </div>
        <hr>
    <?php } ?>
    <a href="chekout.php"> <button class="buy-button">Оформити замовлення</button></a>

</div>