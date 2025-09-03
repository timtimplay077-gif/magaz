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

include('data/baner.php');
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
include('data/baner.php');
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
    <title><?php print_r($row["name"]) ?></title>
</head>

<body>
    <?php

    ?>
    <div class="head unselectable">
        <div class="block">
            <a class="logo" href="index.php"><img src="img/kanskrop_logo.png" alt="KansKrop"></a>
            <form method="GET" class="input_head" action="index.php">
                <a href="index.php"> <label>
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <input type="text" placeholder="Я шукаю..." name="search" value="">
                        <button type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
                    </label></a>

            </form>
            <div class="icons_head">
                <?php if ($isLoggedIn): ?>
                    <?php include("dropdown.php") ?>
                    <button onclick="toggleMenu()"><i class="fa-regular fa-user"></i></button>
                <?php else: ?>
                    <button onclick="openLogin()"><?php include("auth.php"); ?></button>
                <?php endif; ?>

                <?php if ($isLoggedIn): ?>
                    <button onclick="openCartModal()" class="cart-btn">
                        <i class="fa-solid fa-cart-shopping"></i>
                        <?php if ($cart_count > 0): ?>
                            <span class="cart-counter"><?= $cart_count ?></span>
                        <?php endif; ?>
                    </button>
                <?php else: ?>
                    <button onclick="alert('Спочатку авторизуйтесь!')" class="cart-btn">
                        <i class="fa-solid fa-cart-shopping"></i>
                        <?php if (isset($_SESSION['cart']) && array_sum($_SESSION['cart']) > 0): ?>
                            <span class="cart-counter"><?= array_sum($_SESSION['cart']) ?></span>
                        <?php endif; ?>
                    </button>
                <?php endif; ?>
            </div>
        </div>
    </div>
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
                <img class="slider_product" src="<?php print_r($row['img']); ?>" alt="">
                <div class="product_photo_slider"> <?php
                $images = "";
                for ($i = 0; $i < $db_image_query->num_rows; $i++) {
                    $db_image_row = $db_image_query->fetch_assoc();
                    $images .= "'" . $db_image_row["img"] . "',";
                    $img = $db_image_row["img"];
                    ?>
                        <img onclick="set_mimiImg('<?= $i ?>')" src="<?php print_r($img) ?>" alt="">
                    <?php } ?>
                </div>
            </div>
            <script>
                const slider_wrapper_product = [<?= $images ?>];
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
                            <?= $isInCart ? 'У кошику' : 'Купити' ?>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="product_delivery_payment unselectable">
        <div class="block">
            <div class="delivery">
                <h2>Доставка</h2>
                <a href="https://novaposhta.ua/shipping-cost/"><img src="payment/novaposhta.svg" alt="">Доставка Новою
                    Поштою</a>
                <p class="delivery_p">Адреси найближчих відділень дивитися на карті</p>
                <a href="https://www.ukrposhta.ua/ua/taryfy-ukrposhta-standart" class="delivery_a"><img
                        src="payment/ukrposhta.svg" alt="">
                    <p class="delivery_p1">Доставка Укр поштою</p>
                </a>
                <a href="#location"><img src="payment/pickup.svg" alt="">Самовивіз (м.Кропивницький) Безкоштовно</a>
            </div>
            <div class="payment">
                <h2>Оплата</h2>
                <div class="paymant_photo">
                    <img src="payment/1.svg" alt="">
                    <img src="payment/2.svg" alt="">
                    <img src="payment/4.svg" alt="">
                    <img src="payment/5.svg" alt="">
                </div>


            </div>
        </div>
    </div>
    <?php if (!empty($row['aboutproduct'])): ?>
        <div class="product_description unselectable">
            <div class="block" id="about">
                <h2 class="product_name">Усе про <?php print_r(value: $row['name']) ?></h2>
                <p><?php print_r(value: $row['aboutproduct']) ?></p>
            </div>
        </div>
    <?php endif; ?>
    </div>
    <div class="block">
        <div class="characteristics" id="char">
            <h2>Характеристика <?php print_r($row["name"]) ?></h2>
            <p><?php print_r($row["characteristic_1"]) ?></p>
            <p><?php print_r($row["characteristic_2"]) ?></p>
            <p><?php print_r($row["characteristic_3"]) ?></p>
            <p><?php print_r($row["characteristic_4"]) ?></p>
            <p><?php print_r($row["characteristic_5"]) ?></p>
            <p><?php print_r($row["characteristic_6"]) ?></p>
            <p><?php print_r($row["characteristic_7"]) ?></p>
            <p><?php print_r($row["characteristic_8"]) ?></p>
            <p><?php print_r($row["characteristic_9"]) ?></p>
            <p><?php print_r($row["characteristic_10"]) ?></p>
        </div>
    </div>

    <div class="banner-blocks-container2 unselectable">
        <div class="block">
            <?php
            foreach ($data_baner1 as $key => $value) { ?>
                <div class="card2">
                    <img src="<?= $value['img'] ?>" alt="" class="logo_card">
                    <h3><?= $value['name'] ?></h3>
                    <p><?= $value['text'] ?></p>
                    </p>
                </div>
            <?php } ?>
        </div>
    </div>
    <div class="logo_end">
        <div class="block">
            <div>
                <img src="img/kanskrop_logo.png" alt="">
            </div>
            <div class="iframe" id="location">
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m10!1m8!1m3!1d195.92550423792767!2d32.28413667954254!3d48.51912323100282!3m2!1i1024!2i768!4f13.1!5e1!3m2!1suk!2sua!4v1756587377844!5m2!1suk!2sua"
                    width="450" height="300" style="border-radius: 15px; border: 1px solid lightgray;"
                    referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>

        </div>
    </div>
    <div class="contact unselectable">
        <div class="block">
            <div class="card3">
                <p><img src="contact/phone.png" alt="" class="baner2_img">Номер телефона:⠀<snap class="phone_number">
                        +380 500 534 408</snap>
                </p>
                <p><img src="contact/gmail.png" alt="" class="baner2_img">Наша пошта:⠀<snap class="phone_number">
                        admin@kanskrop.com</snap>
                <p><img src="contact/location.png" alt="" class="baner2_img">м.Кропивницький</p>
            </div>
            <div class="ourVT">
                <a href="https://t.me/kanskrop"><img src="contact/telegram.png" alt="" class="contact_logo">
                    <p>Telegram</p>
                </a>
                <a href="viber://chat?number=%2B380500534408"><img src="contact/viber.png" alt="" class="contact_logo">
                    <p>Viber</p>
                </a>
            </div>
        </div>
    </div>
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