<?php
include('data/session_start.php');
include('data/database.php');
include('data/discounts.php');
include('productBasket.php');
if (isset($_SESSION['logout_success'])) {
    $logout_message = $_SESSION['logout_success'];
    unset($_SESSION['logout_success']);
    echo '<script>
    document.addEventListener("DOMContentLoaded", function() {
        showNotification("' . $logout_message . '", "success");
    });
    </script>';
}

include('data/baner2.php');
include('data/category.php');
$isLoggedIn = isset($_SESSION['user_id']) && $_SESSION['user_id'] > 0;
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
$cart_count = 0;
if ($isLoggedIn) {
    $count_sql = "SELECT SUM(count) as total FROM basket WHERE user_id = ?";
    $count_stmt = $db_conn->prepare($count_sql);
    $count_stmt->bind_param("i", $user_id);
    $count_stmt->execute();
    $count_result = $count_stmt->get_result();
    if ($count_result) {
        $count_row = $count_result->fetch_assoc();
        $cart_count = $count_row['total'] ?? 0;
    }
    $count_stmt->close();
} elseif (isset($_SESSION['cart'])) {
    $cart_count = array_sum($_SESSION['cart']);
}
include('data/baner2.php');
include('data/category.php');
include('data/user_data.php');
$id = $_GET['id'];
$db_sql = "SELECT * FROM products WHERE id = '$id'";
$tabl = $db_conn->query($db_sql);
$row = $tabl->fetch_assoc();

function dd($data): void
{
}
$db_image_sql = "SELECT * FROM `productimages` WHERE `product_Id` = $id";
$db_image_query = $db_conn->query($db_image_sql);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <link rel="stylesheet" href="css/shop.css?">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Open+Sans:ital,wght@0,300..800;1,300..800&family=Poiret+One&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
    <script src="https://kit.fontawesome.com/ee9963f31c.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="css/adaptive.css?">
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">
    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
    <title><?php print_r($row["name"]) ?></title>
</head>

<body>
    <?php include("components/header.php");
    ?>
    <div class="about_product unselectable">
        <div class="block">
            <a href="#about">Усе про товар</a>
            <a href="#char">Характеристики</a>
        </div>
    </div>
    <div class="block unselectable">
        <h3 class="product_name"><?php print_r($row["name"]) ?></h3>
    </div>

    <div class="product_row unselectable">
        <div class="block">
            <div class="slider_wrapper2">
                <?php if ($db_image_query->num_rows > 0) { ?>
                    <div class="arrow_l1" onclick="slider_product('left')">
                        <i class="fa-solid fa-chevron-left"></i>
                    </div>
                <?php } ?>
                <?php if ($db_image_query->num_rows > 0) { ?>
                    <div class="arrow_r1" onclick="slider_product('right')">
                        <i class="fa-solid fa-chevron-right"></i>
                    </div>
                <?php } ?>

                <img class="slider_product" src="<?php print_r($row['img']); ?>" alt="<?php print_r($row["name"]) ?>">

                <?php if ($db_image_query->num_rows > 0) { ?>
                    <div class="product_photo_slider">
                        <?php
                        $images = "";
                        $db_image_query->data_seek(0);
                        for ($i = 0; $i < $db_image_query->num_rows; $i++) {
                            $db_image_row = $db_image_query->fetch_assoc();
                            $images .= "'" . $db_image_row["img"] . "',";
                            $img = $db_image_row["img"];
                            $active_class = $i === 0 ? 'active' : '';
                            ?>
                            <img class="<?php echo $active_class; ?>" onclick="set_mimiImg('<?= $i ?>')"
                                src="<?php print_r($img) ?>" alt="Thumbnail <?= $i + 1 ?>">
                        <?php } ?>
                    </div>
                <?php } ?>
            </div>
            <script>
                const slider_wrapper_product = [<?= rtrim($images, ',') ?>];
            </script>

            <div class="product_row_about">
                <div class="status">
                    <p class="stock_status">В наявності</p>
                </div>
                <div class="product_manufacturer">
                    <p>Код: <?php print_r($row["productСode"]) ?></p>
                </div>
                <?php
                $original_price = $row['price'];
                $modifier = $row['price_modifier'] ?? 0;
                $discount_price = $original_price * (1 + $modifier / 100);
                if ($isLoggedIn && isset($user_row['sale']) && $user_row['sale'] > 0) {
                    $discount_price = $discount_price * (1 - $user_row['sale'] / 100);
                }
                ?>
                <div class="product_row_price">
                    <?php
                    $original_price = $row['price'];
                    $modifier = $row['price_modifier'] ?? 0;
                    $base_price = $original_price * (1 + $modifier / 100);
                    $discount_price = $base_price;
                    $has_discount = false;

                    if ($isLoggedIn && isset($user_row['sale']) && $user_row['sale'] > 0) {
                        $discount_price = $base_price * (1 - $user_row['sale'] / 100);
                        $has_discount = true;
                    }
                    ?>
                    <div class="price_product_row">
                        <div class="price-container">
                            <?php if ($has_discount): ?>
                                <span class="old-price"><?= number_format($base_price, 2) ?> ₴</span>
                            <?php endif; ?>
                            <div style="display:flex; align-items: center;">
                                <span class="new-price <?= $has_discount ? 'discounted' : '' ?>">
                                    <?= number_format($discount_price, 2) ?> ₴
                                </span>
                                <?php if ($has_discount): ?>
                                    <span class="discount-badge">-<?= $user_row['sale'] ?>%</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php if (!empty($row['count']) && $row['count'] > 0): ?>
                        <div class="product-count-info" style="margin-top: 20px; font-size: 18px; color: #666;">
                            Упаковка: <?= htmlspecialchars($row['count']) ?> шт.
                        </div>
                    <?php endif; ?>
                    <div class="product_row_about_buy">
                        <?php
                        $product_id = $row['id'];
                        $isInCart = in_array($product_id, array_column($basket_items, 'id'));
                        ?>
                        <button class="buy-btn <?= $isInCart ? 'in-cart' : '' ?>"
                            onclick="addToCart(<?= $product_id ?>, event)" style="width:190px;">
                            <?php if ($isInCart): ?>
                                <i class="fa-solid fa-check"></i> У кошику
                            <?php else: ?>
                                <i class="fa-solid fa-cart-plus"></i> Купити
                            <?php endif; ?>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="block">
        <?php if (!empty($row['aboutproduct'])): ?>
            <div class="section">
                <div class="section-header">
                    <h2><i class="fas fa-info-circle"></i> Усе про Товар</h2>
                </div>
                <div class="section-content">
                    <p class="about-content"><?php print_r(value: $row['aboutproduct']) ?></p>
                </div>
            </div>
        <?php endif; ?>
        <div class="section">
            <div class="section-header">
                <h2><i class="fas fa-list-alt"></i> Характеристики Товару <?php print_r($row["name"]) ?></h2>
            </div>
            <div class="section-content">
                <div class="characteristics-grid">
                    <?php if (!empty($row['characteristic_1'])): ?>
                        <div class="char-item">
                            <p><?php print_r($row["characteristic_1"]) ?></p>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($row['characteristic_2'])): ?>
                        <div class="char-item">
                            <p><?php print_r($row["characteristic_2"]) ?></p>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($row['characteristic_3'])): ?>
                        <div class="char-item">
                            <p><?php print_r($row["characteristic_3"]) ?></p>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($row['characteristic_4'])): ?>
                        <div class="char-item">
                            <p><?php print_r($row["characteristic_4"]) ?></p>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($row['characteristic_5'])): ?>
                        <div class="char-item">
                            <p><?php print_r($row["characteristic_5"]) ?></p>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($row['characteristic_6'])): ?>
                        <div class="char-item">
                            <p><?php print_r($row["characteristic_6"]) ?></p>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($row['characteristic_7'])): ?>
                        <div class="char-item">
                            <p><?php print_r($row["characteristic_7"]) ?></p>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($row['characteristic_8'])): ?>
                        <div class="char-item">
                            <p><?php print_r($row["characteristic_8"]) ?></p>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($row['characteristic_9'])): ?>
                        <div class="char-item">
                            <p><?php print_r($row["characteristic_9"]) ?></p>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($row['characteristic_10'])): ?>
                        <div class="char-item">
                            <p><?php print_r($row["characteristic_10"]) ?></p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <div class="delivery-payment-section">
        <div class="block">
            <h2 class="section-title">Доставка та оплата</h2>
            <div class="delivery-grid">
                <div class="delivery-option">
                    <div class="delivery-header">
                        <div class="delivery-icon">
                            <img src="payment/novaposhta.svg" alt="Нова Пошта">
                        </div>
                        <h3 class="delivery-title">Нова Пошта</h3>
                    </div>
                    <p class="delivery-description">
                        Швидка та надійна доставка по всій Україні. Відправка в день замовлення.
                    </p>
                    <a href="https://novaposhta.ua/shipping-cost/" class="delivery-link" target="_blank">
                        Детальніше про тарифи
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
                <div class="delivery-option">
                    <div class="delivery-header">
                        <div class="delivery-icon">
                            <img src="payment/ukrposhta.svg" alt="Укрпошта">
                        </div>
                        <h3 class="delivery-title">Укрпошта</h3>
                    </div>
                    <p class="delivery-description">
                        Економна доставка у віддалені куточки України. Ідеально для невеликих посилок.
                    </p>
                    <a href="https://www.ukrposhta.ua/ua/taryfy-ukrposhta-standart" class="delivery-link"
                        target="_blank">
                        Тарифи Укрпошти
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
                <div class="delivery-option">
                    <div class="delivery-header">
                        <div class="delivery-icon">
                            <img src="payment/pickup.svg" alt="Самовивіз">
                        </div>
                        <h3 class="delivery-title">Самовивіз</h3>
                    </div>
                    <p class="delivery-description">
                        Заберіть замовлення самотужки з нашого магазину в м. Кропивницький. Безкоштовно.
                    </p>
                    <a href="https://maps.app.goo.gl/Tyci6VFU98RzmDXS8" class="delivery-link">
                        Дивитися на карті
                        <i class="fas fa-map-marker-alt"></i>
                    </a>
                </div>
                <div class="delivery-option">
                    <div class="delivery-header">
                        <div class="delivery-icon">
                            <i class="fas fa-truck" style="font-size: 24px; color: #4CAF50;"></i>
                        </div>
                        <h3 class="delivery-title">Кур'єрська доставка</h3>
                    </div>
                    <p class="delivery-description">
                        Доставка кур'єром до дверей у м. Кропивницький. Зручно та швидко.
                    </p>
                    <span class="delivery-link">
                        Деталі у менеджера
                        <i class="fas fa-phone"></i>
                    </span>
                </div>
            </div>
            <h3 style="text-align: center; margin: 40px 0 20px; color: #2d3748; font-size: 1.5rem;">
                Способи оплати
            </h3>
            <div class="payment-methods">
                <div class="payment-method">
                    <img src="payment/1.svg" alt="Payment Method 1">
                </div>
                <div class="payment-method">
                    <img src="payment/2.svg" alt="Payment Method 2">
                </div>
                <div class="payment-method">
                    <img src="payment/4.svg" alt="Payment Method 4">
                </div>
                <div class="payment-method">
                    <img src="payment/5.svg" alt="Payment Method 5">
                </div>
            </div>
        </div>
    </div>
    <div class="benefits-section unselectable">
        <div class="block">
            <div class="benefits-header">
                <h2>Наші переваги</h2>
                <p>Чому клієнти обирають нас</p>
                <div class="benefits-divider"></div>
            </div>
            <div class="benefits-grid">
                <?php foreach ($data_baner1 as $value): ?>
                    <div class="benefit-card">
                        <div class="benefit-icon">
                            <img src="<?= $value['img'] ?>" alt="<?= htmlspecialchars($value['name']) ?>">
                            <div class="icon-overlay"></div>
                        </div>
                        <div class="benefit-content">
                            <h3><?= htmlspecialchars($value['name']) ?></h3>
                            <p><?= htmlspecialchars($value['text']) ?></p>
                        </div>
                        <div class="benefit-hover-effect"></div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <div class="logo-map-section unselectable">
        <div class="block">
            <div class="logo-map-grid">
                <div class="logo-card">
                    <div class="logo-content">
                        <img src="img/kanskrop_logo.png" alt="KansKrop" class="main-logo">
                        <div class="logo-overlay"></div>
                        <div class="logo-glow"></div>
                    </div>
                    <div class="logo-info">
                        <h3>КанцКроп</h3>
                        <p>Інтернет-магазин канцелярських товарів</p>
                        <div class="logo-features">
                            <span class="feature-tag">Якість</span>
                            <span class="feature-tag">Надійність</span>
                            <span class="feature-tag">Швидка доставка</span>
                        </div>
                    </div>
                </div>
                <div class="map-card">
                    <div class="map-header">
                        <h3>Ми знаходимось</h3>
                        <div class="map-divider"></div>
                    </div>
                    <div class="map-container">
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m10!1m8!1m3!1d195.92550423792767!2d32.28413667954254!3d48.51912323100282!3m2!1i1024!2i768!4f13.1!5e1!3m2!1suk!2sua!4v1756587377844!5m2!1suk!2sua"
                            width="100%" height="300" style="border: none;" referrerpolicy="no-referrer-when-downgrade"
                            class="map-iframe" loading="lazy" allowfullscreen>
                        </iframe>
                        <div class="map-overlay"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
    include("contact/contact_end.php")
        ?>

    <script src="js/main.js"></script>
    <script>
        $(document).ready(function () {
            $('.about_product a[href^="#"]').on('click', function (e) {
                e.preventDefault();

                var target = this.hash;
                var $target = $(target);

                $('html, body').animate({
                    'scrollTop': $target.offset().top
                }, 800, 'swing');
            });
        });
    </script>
    <?php
    if ($isLoggedIn) {
        include("dropdown.php");
    }
    ?>

</body>

</html>