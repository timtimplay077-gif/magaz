<?php
include('data/session_start.php');
include('data/database.php');

// Проверка авторизации и получение данных пользователя
$isLoggedIn = isset($_SESSION['user_id']) && $_SESSION['user_id'] > 0;
$user_row = [];
if ($isLoggedIn) {
    $user_id = $_SESSION['user_id'];
    $user_sql = "SELECT * FROM users WHERE id = ?";
    $user_stmt = $db_conn->prepare($user_sql);
    $user_stmt->bind_param("i", $user_id);
    $user_stmt->execute();
    $user_result = $user_stmt->get_result();
    $user_row = $user_result->fetch_assoc();
    $user_stmt->close();
}

$basket_items = [];
$total_items = 0;
$total_sum = 0;

// Если пользователь авторизован
if ($isLoggedIn) {
    $basket_sql = "SELECT b.*, p.*, b.count as basket_count 
                   FROM basket b 
                   JOIN products p ON b.product_id = p.id 
                   WHERE b.user_id = ?";
    $stmt = $db_conn->prepare($basket_sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $basket_query = $stmt->get_result();

    if ($basket_query && $basket_query->num_rows > 0) {
        while ($item = $basket_query->fetch_assoc()) {
            $price = $item['price'];
            $modifier = $item['price_modifier'] ?? 0; // % скидки/наценки
            $final_price = $price * (1 + $modifier / 100);

            // Применяем скидку пользователя
            if (isset($user_row['sale']) && $user_row['sale'] > 0) {
                $final_price = $final_price * (1 - $user_row['sale'] / 100);
            }

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
    $stmt->close();
}
// Если пользователь не авторизован, берём корзину из сессии
elseif (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    $product_ids = array_keys($_SESSION['cart']);
    if (!empty($product_ids)) {
        $in = implode(',', array_map('intval', $product_ids));
        $products_sql = "SELECT * FROM products WHERE id IN ($in)";
        $products_query = $db_conn->query($products_sql);

        if ($products_query && $products_query->num_rows > 0) {
            while ($product = $products_query->fetch_assoc()) {
                $quantity = $_SESSION['cart'][$product['id']];
                $modifier = $product['price_modifier'] ?? 0;
                $final_price = $product['price'] * (1 + $modifier / 100);
                // Для гостей скидка пользователя не применяется
                $item_total = $final_price * $quantity;

                $basket_items[] = [
                    'id' => $product['id'],
                    'name' => $product['name'],
                    'img' => $product['img'],
                    'price' => $final_price,
                    'quantity' => $quantity,
                    'total' => $item_total
                ];

                $total_items += $quantity;
                $total_sum += $item_total;
            }
        }
    }
}

// Функция склонения слова "товар"
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

<!-- Кнопка корзины -->
<button onclick="openCart()" class="cart-btn" data-loggedin="<?= $isLoggedIn ? '1' : '0' ?>">
    <i class="fa-solid fa-cart-shopping"></i>
    <?php if ($total_items > 0): ?>
        <span class="cart-counter"><?= $total_items ?></span>
    <?php endif; ?>
</button>

<!-- Модальное окно корзины -->
<div class="modal modal-basket" id="cartModal" style="display:none;">
    <div class="cart-header">
        <div class="flex_close">
            <div class="cart-title">
                <p>Кошик</p>
            </div>
            <button class="delete-button" onclick="closeCartModal()">
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
                        <img src="<?= $item['img'] ?>" alt="<?= htmlspecialchars($item['name']) ?>">
                    </div>
                    <div class="name-wrapper">
                        <p><?= htmlspecialchars($item['name']) ?></p>
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
    const cartData = {
        items: <?= json_encode($basket_items) ?>,
        totalItems: <?= $total_items ?>,
        totalSum: <?= $total_sum ?>,
        isLoggedIn: <?= $isLoggedIn ? 'true' : 'false' ?>
    };
</script>