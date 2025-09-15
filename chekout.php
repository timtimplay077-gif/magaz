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
$total = 0;
$total_items = 0;

$basket_sql = "SELECT b.*, p.*, b.count as basket_count 
               FROM basket b 
               JOIN products p ON b.product_id = p.id 
               WHERE b.user_id = '$user_id'";
$basket_query = $db_conn->query($basket_sql);

if ($basket_query && $basket_query->num_rows > 0) {
    while ($item = $basket_query->fetch_assoc()) {
        $price = $item['price'];
        $modifier = $item['price_modifier'] ?? 0;
        $final_price = $price * (1 + $modifier / 100);
        $has_discount = false;
        $original_price = $final_price;
        if (isset($user_row['sale']) && $user_row['sale'] > 0) {
            $final_price = $final_price * (1 - $user_row['sale'] / 100);
            $has_discount = true;
        }

        $quantity = $item['basket_count'];
        $item_total = $final_price * $quantity;

        $basket_items[] = [
            'id' => $item['product_id'],
            'name' => $item['name'],
            'img' => $item['img'],
            'price' => $final_price,
            'original_price' => $original_price,
            'quantity' => $quantity,
            'total' => $item_total,
            'has_discount' => $has_discount,
            'productCode' => $item['productCode'] ?? ''
        ];

        $total_items += $quantity;
        $total += $item_total;
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
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">
    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
    <title>Оформлення заказу</title>
</head>

<body>
    <?php include("components/header.php");
    ?>
    <div class="whatWeHave unselectable">
        <div class="block">
            <div class="category-header">
                <div class="category-card">
                    <div class="category-icon">
                        <img src="categoty/school-material.png" alt="Канцелярія">
                        <div class="icon-hover-effect"></div>
                        <div class="icon-glow"></div>
                    </div>
                    <a href="index.php" class="category-link">
                        <span class="link-text">Канцелярія</span>
                        <div class="link-underline"></div>
                        <div class="link-hover-effect"></div>
                    </a>
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
                            <select id="region" name="region" required>
                                <option value="" disabled selected>Оберіть область</option>
                                <option value="Вінницька область">Вінницька область</option>
                                <option value="Волинська область">Волинська область</option>
                                <option value="Дніпропетровська область">Дніпропетровська область</option>
                                <option value="Донецька область">Донецька область</option>
                                <option value="Житомирська область">Житомирська область</option>
                                <option value="Закарпатська область">Закарпатська область</option>
                                <option value="Запорізька область">Запорізька область</option>
                                <option value="Івано-Франківська область">Івано-Франківська область</option>
                                <option value="Київська область">Київська область</option>
                                <option value="Кіровоградська область">Кіровоградська область</option>
                                <option value="Луганська область">Луганська область</option>
                                <option value="Львівська область">Львівська область</option>
                                <option value="Миколаївська область">Миколаївська область</option>
                                <option value="Одеська область">Одеська область</option>
                                <option value="Полтавська область">Полтавська область</option>
                                <option value="Рівненська область">Рівненська область</option>
                                <option value="Сумська область">Сумська область</option>
                                <option value="Тернопільська область">Тернопільська область</option>
                                <option value="Харківська область">Харківська область</option>
                                <option value="Херсонська область">Херсонська область</option>
                                <option value="Хмельницька область">Хмельницька область</option>
                                <option value="Черкаська область">Черкаська область</option>
                                <option value="Чернівецька область">Чернівецька область</option>
                                <option value="Чернігівська область">Чернігівська область</option>
                                <option value="м. Київ">м. Київ</option>
                                <option value="м. Севастополь">м. Севастополь</option>
                                <option value="Автономна Республіка Крим">Автономна Республіка Крим</option>
                            </select>

                            <br><br>
                            <label for="address">* Адреса (необов'язково)</label><br>
                            <input type="text" id="address" name="address" placeholder="Адреса">
                            <br><br>
                            <label for="nova_poshta">* Номер відділення Нової Пошти (необов'язково)</label><br>
                            <input type="text" id="nova_poshta" name="nova_poshta" placeholder="Наприклад: №12">
                        </div>
                    </div>
                </div>
            </div>
            <div class="your_oder">
                <?php
                foreach ($basket_items as $item):
                    ?>
                    <div class="oder_item">
                        <a href="product.php?id=<?= $item['id'] ?>">
                            <img src="<?= $item['img'] ?>" alt="<?= $item['name'] ?>">
                            <p class="order_name"><?= $item['name'] ?></p>
                            <p class="order_code">Код: <?= $item['productCode'] ?></p>
                            <p class="order_quantity">Кількість: <?= $item['quantity'] ?> шт.</p>
                            <p class="order_price">
                                <?php if ($item['has_discount']): ?>
                                    <span style="text-decoration: line-through; color: #999;">
                                        <?= number_format($item['original_price'] * $item['quantity'], 2) ?> ₴
                                    </span><br>
                                <?php endif; ?>
                                <?= number_format($item['price'], 2) ?> ₴ ×
                                <?= $item['quantity'] ?> = <?= number_format($item['total'], 2) ?> ₴
                            </p>
                        </a>
                    </div>
                    <?php
                endforeach;
                ?>
            </div>
            <p class="oder_total">Загальна сума: <b><?= number_format($total, 2) ?> ₴</b></p>
            <h3 class="oder_total">Ваше замовлення (<?= $total_items ?> товарів)</h3>
            <div class="order_ready">
                <input type="hidden" name="total_amount" value="<?= $total ?>">
                <input type="hidden" name="total_items" value="<?= $total_items ?>">
                <button type="submit" class="order_ready_button">Оформлення замовлення</button>
            </div>
        </form>
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
    <?php
    if ($isLoggedIn) {
        include("dropdown.php");
    }
    ?>
</body>

</html>