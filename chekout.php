<?php
include('data/session_start.php');
include('data/database.php');
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
include('data/baner2.php');
include('data/category.php');
include('data/user_data.php');
$user_id = $_SESSION['user_id'];
$user_sql = "SELECT * FROM users WHERE id = '$user_id'";
$user_result = $db_conn->query($user_sql);
$user_row = $user_result->fetch_assoc();
$basket_items = [];
$basket_sql = "SELECT b.*, p.* FROM basket b 
               JOIN products p ON b.product_id = p.id 
               WHERE b.user_id = '$user_id'";
$basket_query = $db_conn->query($basket_sql);

if ($basket_query && $basket_query->num_rows > 0) {
    while ($basket_row = $basket_query->fetch_assoc()) {
        $basket_items[$basket_row['product_id']] = [
            'count' => $basket_row['count'],
            'productCode' => $basket_row['productCode'] ?? ''
        ];
    }
}
if (empty($basket_items)) {
    header('Location: index.php?message=Кошик порожній');
    exit;
}
?>
<!DOCTYPE html>
<html lang="uk">

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
    <title>Оформлення заказу</title>
</head>

<body>
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
    <div class="whatWeHave unselectable">
        <div class="block">
            <div class="whatWeHave_kans">
                <img src="categoty/school-material.png" alt="Канцелярские товары">
                <div class="categories">
                    <a href="index.php"><button class="categories-button">Категорії</button></a>
                </div>
                <div class="marquee">
                    <span id="marqueeText"></span>
                </div>
            </div>
        </div>
    </div>

    <div class="oder block">Оформлення замовлення</div>

    <div class="block">
        <form action="odercheck.php" method="POST">
            <div class="chekount">
                <div class="ored_adres">
                    <h2>Покупець</h2>
                    <div class="label_chekount">
                        <div style="margin-bottom: 30px;">
                            <label for="firstName">* Ім'я</label><br>
                            <input type="text" id="firstName" name="firstName" placeholder="Ім'я" required
                                value="<?= htmlspecialchars($user_row['first_name'] ?? '') ?>">
                            <br><br>
                            <label for="lastName">* Прізвище</label><br>
                            <input type="text" id="lastName" name="lastName" placeholder="Прізвище" required
                                value="<?= htmlspecialchars($user_row['last_name'] ?? '') ?>">
                        </div>
                        <div>
                            <label for="email">* E-Mail</label><br>
                            <input type="email" id="email" name="email" placeholder="E-Mail" required
                                value="<?= htmlspecialchars($user_row['email'] ?? '') ?>">
                            <br><br>
                            <label for="phone">* Телефон</label><br>
                            <input type="tel" id="phone" name="phone" placeholder="Телефон" required
                                value="<?= htmlspecialchars($user_row['phone'] ?? '') ?>">
                        </div>
                    </div>
                </div>
                <div class="adres_label">
                    <h2>Адреса доставки</h2>
                    <div class="label_adres">
                        <div>
                            <label for="city">* Місто</label><br>
                            <input type="text" id="city" name="city" placeholder="Місто" required>
                            <br><br>
                            <label for="region">* Регіон / Область</label><br>
                            <input type="text" id="region" name="region" placeholder="Регіон / Область" required>
                            <br><br>
                            <label for="address">* Адреса</label><br>
                            <input type="text" id="address" name="address" placeholder="Адреса" required>
                        </div>
                    </div>
                </div>
            </div>
            <div class="your_oder">
                <?php
                $total = 0;
                $total_items = 0;
                foreach ($basket_items as $product_id => $item_data) {
                    $product_sql = "SELECT * FROM products WHERE id = '$product_id'";
                    $product_result = $db_conn->query($product_sql);

                    if ($product_result && $product_result->num_rows > 0) {
                        $product = $product_result->fetch_assoc();
                        $original_price = $product['price'];
                        $final_price = $original_price;
                        if (!empty($product['price_modifier'])) {
                            $final_price *= (1 + $product['price_modifier'] / 100);
                        }
                        if (!empty($user_row['sale']) && $user_row['sale'] > 0) {
                            $final_price *= (1 - $user_row['sale'] / 100);
                        }
                        $quantity = $item_data['count'];
                        $item_total = $final_price * $quantity;
                        $total += $item_total;
                        $total_items += $quantity;
                        ?>
                        <div class="oder_item">
                            <a href="product.php?id=<?= $product['id'] ?>">
                                <img src="<?= $product['img'] ?>" alt="<?= $product['name'] ?>">
                                <p class="order_name"><?= $product['name'] ?></p>
                                <p class="order_code">Код: <?= $item_data['productCode'] ?></p>
                                <p class="order_quantity">Кількість: <?= $quantity ?> шт.</p>
                                <p class="order_price"><?= number_format($final_price, 2) ?> ₴ ×
                                    <?= $quantity ?> = <?= number_format($item_total, 2) ?> ₴
                                </p>
                            </a>
                        </div>
                        <?php
                    }
                }
                ?>
            </div>
            <p class="oder_total">Загальна сума: <b><?= number_format($total, 2) ?> ₴</b></p>
            <h3 class="oder_total">Ваше замовлення (<?= $total_items ?> товарів)</h3>

            <div class="order_ready">
                <button type="submit" class="order_ready_button">Оформлення замовлення</button>
            </div>
        </form>
    </div>

    <div class="banner-blocks-container2">
        <div class="block">
            <?php foreach ($data_baner1 as $value): ?>
                <div class="card2">
                    <img src="<?= $value['img'] ?>" alt="<?= $value['name'] ?>" class="logo_card">
                    <h3><?= $value['name'] ?></h3>
                    <p><?= $value['text'] ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="logo_end">
        <div class="block">
            <div>
                <img src="img/kanskrop_logo.png" alt="KansKrop">
            </div>
            <div class="iframe">
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d554.1606144377334!2d32.284208611360036!3d48.519159446434855!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x40d05d0008bb3049%3A0x75b540cf193b012!2z0JrQsNC90YbQmtGA0L7QvyAvINCa0LDQvdGH0YLQvtCy0LDRgNC4!5e1!3m2!1suk!2snl!4v1754843009070!5m2!1suk!2snl"
                    width="450" height="300" style="border-radius: 15px; border: 1px solid lightgray;"
                    allowfullscreen=""></iframe>
            </div>
        </div>
    </div>

    <div class="contact unselectable">
        <div class="block">
            <div class="card3">
                <p><img src="contact/phone.png" alt="Телефон" class="baner2_img">Номер телефона:
                    <span class="phone_number">+380 500 534 408</span>
                </p>
                <p><img src="contact/gmail.png" alt="Email" class="baner2_img">Наша пошта:
                    <span class="phone_number">admin@kanskrop.com</span>
                </p>
                <p><img src="contact/location.png" alt="Адреса" class="baner2_img">м.Кропивницький</p>
            </div>
            <div class="ourVT">
                <a href="https://t.me/kanskrop"><img src="contact/telegram.png" alt="Telegram" class="contact_logo">
                    <p>Telegram</p>
                </a>
                <a href="viber://chat?number=%2B380500534408"><img src="contact/viber.png" alt="Viber"
                        class="contact_logo">
                    <p>Viber</p>
                </a>
            </div>
        </div>
    </div>
    <script src="js/main.js"></script>
    <?php
    if ($isLoggedIn) {
        include("dropdown.php");
    }
    ?>
</body>

</html>