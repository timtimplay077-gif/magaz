<?php
include('data/session_start.php');
include('data/database.php');
include('data/discounts.php');
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
$total_sum_without_discount = 0;

if ($isLoggedIn) {
    $basket_sql = "SELECT b.id as basket_id, b.user_id, b.product_id, b.count as basket_count, 
                          p.id as product_id, p.name, p.img, p.price, p.price_modifier
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
            $modifier = $item['price_modifier'] ?? 0;
            $final_price = $price * (1 + $modifier / 100);
            $has_discount = false;
            $original_price = $final_price;
            $price_without_discount = $final_price;

            if (isset($user_row['sale']) && $user_row['sale'] > 0) {
                $final_price = $final_price * (1 - $user_row['sale'] / 100);
                $has_discount = true;
            }

            $quantity = $item['basket_count'];
            $item_total = $final_price * $quantity;
            $item_total_without_discount = $price_without_discount * $quantity;

            $basket_items[] = [
                'id' => $item['product_id'],
                'basket_id' => $item['basket_id'],
                'name' => $item['name'],
                'img' => $item['img'],
                'price' => $final_price,
                'original_price' => $original_price,
                'price_without_discount' => $price_without_discount,
                'quantity' => $quantity,
                'total' => $item_total,
                'total_without_discount' => $item_total_without_discount,
                'has_discount' => $has_discount,
                'discount_percent' => isset($user_row['sale']) ? $user_row['sale'] : 0
            ];

            $total_items += $quantity;
            $total_sum += $item_total;
            $total_sum_without_discount += $item_total_without_discount;
        }
    }
}
if ($isLoggedIn && isset($user_row['sale'])) {
    $_SESSION['user_discount'] = $user_row['sale'];
} elseif (!$isLoggedIn) {
    unset($_SESSION['user_discount']);
}

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
<button onclick="openCart()" class="cart-btn" data-loggedin="<?= $isLoggedIn ? '1' : '0' ?>">
    <i class="fa-solid fa-cart-shopping"></i>
    <?php if ($total_items > 0): ?>
        <span class="cart-counter"><?= $total_items ?></span>
    <?php endif; ?>
</button>
<div class="modal modal-basket" id="cartModal" style="display:none; background: #0A0A0A;">
    <div class="cart-header-content">
        <div class="cart-logo-section">
            <div class="cart-logo-wrapper">
                <img src="img/kanskrop_logo.png" alt="Logo" class="cart-logo-img">
                <div class="cart-logo-glow"></div>
            </div>
            <h2 class="cart-title">Кошик</h2>
        </div>
        <button class="cart-close-btn" onclick="closeCartModal()">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>

    <div class="cart-content">
        <div class="cart-items-container" id="cart-items">
            <?php if (!empty($basket_items)): ?>
                <?php foreach ($basket_items as $item): ?>
                    <div class="cart-item" data-id="<?= $item['id'] ?>" data-basket-id="<?= $item['basket_id'] ?>"
                        data-price="<?= $item['price'] ?>" data-discount="<?= $item['discount_percent'] ?>">
                        <div class="cart-item-image">
                            <img src="<?= $item['img'] ?>" alt="<?= htmlspecialchars($item['name']) ?>">
                        </div>
                        <div class="cart-item-details">
                            <h3 class="cart-item-name"><?= htmlspecialchars($item['name']) ?></h3>
                            <div class="cart-item-price">
                                <?php if ($item['has_discount']): ?>
                                    <span class="original-price"><?= number_format($item['price_without_discount'], 2) ?> ₴</span>
                                <?php endif; ?>
                                <span class="final-price <?= $item['has_discount'] ? 'discounted' : '' ?>">
                                    <?= number_format($item['price'], 2) ?> ₴
                                </span>
                            </div>
                        </div>
                        <div class="cart-item-controls">
                            <div class="quantity-controls">
                                <button class="qty-btn minus"
                                    onclick="changeQuantity(this, 'decrease', <?= $item['id'] ?>, <?= $item['discount_percent'] ?>)">-</button>
                                <span class="quantity-count"><?= $item['quantity'] ?></span>
                                <button class="qty-btn plus"
                                    onclick="changeQuantity(this, 'increase', <?= $item['id'] ?>, <?= $item['discount_percent'] ?>)">+</button>
                            </div>
                            <div class="cart-item-total">
                                <span class="item-total-price"><?= number_format($item['total'], 2) ?> ₴</span>
                            </div>
                            <a href="#" class="cart-item-remove" onclick="removeFromCart(<?= $item['id'] ?>); return false;">
                                <i class="fa-solid fa-trash"></i>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="empty-cart-state" id="empty-cart-state" style="display: block;">
                    <div class="empty-cart-icon">
                        <i class="fa-solid fa-cart-shopping"></i>
                    </div>
                    <h3 class="empty-cart-title">Кошик порожній</h3>
                    <p class="empty-cart-message">Додайте товари до кошика, щоб зробити покупку</p>
                    <button class="empty-cart-button" onclick="closeCartModal()">Продовжити покупки</button>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="cart-footer" id="cart-footer" style="<?= empty($basket_items) ? 'display: none;' : '' ?>">
        <div class="cart-summary">
            <div class="summary-row">
                <span class="summary-label">Товарів:</span>
                <span class="summary-value" id="total-items-count"><?= $total_items ?>
                    <?= getItemWord($total_items) ?></span>
            </div>
            <?php if ($total_sum_without_discount > $total_sum): ?>
                <div class="summary-row discount-row">
                    <span class="summary-label">Знижка:</span>
                    <span class="summary-value"
                        id="total-discount">-<?= number_format($total_sum_without_discount - $total_sum, 2) ?> ₴</span>
                </div>
            <?php endif; ?>
            <div class="summary-row total-row">
                <span class="summary-label">До сплати:</span>
                <span class="summary-value" id="total-sum"><?= number_format($total_sum, 2) ?> ₴</span>
            </div>
        </div>
        <div class="cart-actions">
            <a href="chekout.php" class="checkout-button">
                <i class="fa-solid fa-credit-card"></i>
                Оформити замовлення
            </a>
            <button class="continue-shopping-btn" onclick="closeCartModal()">
                <i class="fa-solid fa-arrow-left"></i>
                Продовжити покупки
            </button>
        </div>
    </div>
</div>
<script>
    const cartData = {
        items: <?= json_encode($basket_items) ?>,
        totalItems: <?= $total_items ?>,
        totalSum: <?= $total_sum ?>,
        totalSumWithoutDiscount: <?= $total_sum_without_discount ?>,
        isLoggedIn: <?= $isLoggedIn ? 'true' : 'false' ?>,
        discountPercent: <?= isset($_SESSION['user_discount']) ? $_SESSION['user_discount'] : 0 ?>
    };

    function applyDiscount(price, discountPercent) {
        if (discountPercent > 0) {
            return price * (1 - discountPercent / 100);
        }
        return price;
    }
</script>