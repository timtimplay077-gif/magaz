<?php
include('data/database.php');

// Получаем данные корзины
$basket_items = [];
$total_items = 0;
$total_sum = 0;

if (isset($_SESSION['user_id'])) {
    // Для авторизованных пользователей - из БД
    $user_id = $_SESSION['user_id'];
    $basket_sql = "SELECT b.*, p.*, b.count as basket_count 
                   FROM basket b 
                   JOIN products p ON b.product_id = p.id 
                   WHERE b.user_id = '$user_id'";
    $basket_query = $db_conn->query($basket_sql);

    if ($basket_query && $basket_query->num_rows > 0) {
        while ($item = $basket_query->fetch_assoc()) {
            $price = $item['price'];
            $final_price = $price; // Базовая цена
            $quantity = $item['basket_count'];
            $item_total = $final_price * $quantity;

            $basket_items[] = [
                'id' => $item['id'],
                'name' => $item['name'],
                'img' => $item['img'],
                'price' => $final_price,
                'quantity' => $quantity,
                'total' => $item_total
            ];

            $total_items += $quantity;
            $total_sum += $item_total;
        }
    }
} elseif (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    // Для неавторизованных - из сессии
    $product_ids = array_keys($_SESSION['cart']);
    if (!empty($product_ids)) {
        $in = implode(',', array_map('intval', $product_ids));
        $products_sql = "SELECT * FROM products WHERE id IN ($in)";
        $products_query = $db_conn->query($products_sql);

        if ($products_query && $products_query->num_rows > 0) {
            while ($product = $products_query->fetch_assoc()) {
                $quantity = $_SESSION['cart'][$product['id']];
                $item_total = $product['price'] * $quantity;

                $basket_items[] = [
                    'id' => $product['id'],
                    'name' => $product['name'],
                    'img' => $product['img'],
                    'price' => $product['price'],
                    'quantity' => $quantity,
                    'total' => $item_total
                ];

                $total_items += $quantity;
                $total_sum += $item_total;
            }
        }
    }
}
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
        <?php if (!empty($basket_items)): ?>
            <?php foreach ($basket_items as $item): ?>
                <div class="header_card_product" data-id="<?= $item['id'] ?>" data-price="<?= $item['price'] ?>">
                    <div class="delete-wrapper">
                        <a href="#" class="delete-btn" onclick="removeFromCart(<?= $item['id'] ?>); return false;">
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
                        <span class="price"><?= number_format($item['total'], 2) ?> ₴</span>
                    </div>
                    <div class="quantity-wrapper">
                        <button class="qty-btn minus" onclick="changeQuantity(this, 'decrease', <?= $item['id'] ?>)">-</button>
                        <span class="count"><?= $item['quantity'] ?></span>
                        <button class="qty-btn plus" onclick="changeQuantity(this, 'increase', <?= $item['id'] ?>)">+</button>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="empty-cart">Кошик порожній</p>
        <?php endif; ?>
    </div>

    <div class="cart-footer">
        <span id="cart-count">В кошику: <?= $total_items ?> <?= getItemWord($total_items) ?></span>
        <span id="cart-total">на суму: <?= number_format($total_sum, 2) ?> ₴</span>
    </div>

    <?php if (!empty($basket_items)): ?>
        <a href="chekout.php" class="buy-button">Оформити замовлення</a>
    <?php endif; ?>
</div>

<script>
    // Передаем данные из PHP в JavaScript
    const cartData = {
        items: <?= json_encode($basket_items) ?>,
        totalItems: <?= $total_items ?>,
        totalSum: <?= $total_sum ?>,
        isLoggedIn: <?= isset($_SESSION['user_id']) ? 'true' : 'false' ?>
    };

    console.log('Cart data loaded:', cartData);
</script>

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
<script src="js/main.js"></script>
</body>

</html>