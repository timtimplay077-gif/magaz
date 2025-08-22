<?php
include('data/database.php');

if (!isset($_SESSION['user_id'])) {
    // Показываем пустую корзину для неавторизованных
    echo '<div class="modal modal-basket" id="cartModal">';
    echo '    <div class="cart-header"> <div class="flex_close"><div class="cart-title"><p>Кошик</p></div><button class="delete-button" onclick="closeCart()"><img src="img/close.png" alt="Закрити"></button></div></div>';
    echo '    <div id="cart-items"><p class="empty-cart">Кошик порожній</p></div>';
    echo '    <div class="cart-footer">';
    echo '        <span id="cart-count">В кошику: 0 товарів</span>';
    echo '        <span id="cart-total">на суму: 0 ₴</span>';
    echo '    </div>';
    echo '</div>';
    exit;
}

$user_id = $_SESSION['user_id'];
$basket_sql = "SELECT b.*, p.* FROM basket b 
               JOIN products p ON b.product_id = p.id 
               WHERE b.user_id = '$user_id'";
$basket_query = $db_conn->query($basket_sql);
$user_sql = "SELECT sale FROM users WHERE id = '$user_id'";
$user_result = $db_conn->query($user_sql);
$user_discount = $user_result->fetch_assoc()['sale'] ?? 0;
?>

<div class="modal modal-basket" id="cartModal">
    <div class="cart-header">
        <div class="flex_close">
            <div class="cart-title">
                <p>Кошик</p>
            </div>
            <button class="delete-button" onclick="closeCart()">
                <img src="img/close.png" alt="Закрити">
            </button>
        </div>
    </div>

    <div id="cart-items">
        <?php if ($basket_query->num_rows > 0): ?>
            <?php while ($item = $basket_query->fetch_assoc()):
                $price = $item['price'];
                $final_price = $user_discount > 0 ? $price * (1 - $user_discount / 100) : $price;
                $total_price = $final_price * $item['count'];
                ?>
                <div class="header_card_product" data-id="<?= $item['id'] ?>" data-price="<?= $final_price ?>">
                    <div class="delete-wrapper">
                        <a href="#" class="delete-btn" onclick="removeFromCart(this); return false;">
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
                        <span class="price"><?= number_format($total_price, 2) ?> ₴</span>
                    </div>
                    <div class="quantity-wrapper">
                        <button class="qty-btn minus" onclick="changeQuantity(this, 'decrease')">-</button>
                        <span class="count"><?= $item['count'] ?></span>
                        <button class="qty-btn plus" onclick="changeQuantity(this, 'increase')">+</button>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p class="empty-cart">Кошик порожній</p>
        <?php endif; ?>
    </div>

    <div class="cart-footer">
        <span id="cart-count">В кошику: 0 товарів</span>
        <span id="cart-total">на суму: 0 ₴</span>
    </div>

    <a href="chekout.php" class="buy-button">Оформити замовлення</a>
</div>

<script src="js/main.js"></script>
<?php
function getItemWord($count)
{
    if ($count == 0)
        return 'товарів';
    if ($count % 10 === 1 && $count % 100 !== 11)
        return 'товар';
    if ($count % 10 >= 2 && $count % 10 <= 4 && ($count % 100 < 10 || $count % 100 >= 20))
        return 'товари';
    return 'товарів';
}
?>