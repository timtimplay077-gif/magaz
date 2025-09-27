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
$min_order_amount = 200;
if ($total < $min_order_amount) {
    header('Location: index.php?message=Мінімальна сума замовлення ' . $min_order_amount . ' грн');
    exit;
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
    <style>
        :root {
            --primary-color: #4CAF50;
            --primary-hover: #1b5a1dff;
            --success-color: #28a745;
            --warning-color: #ffc107;
            --text-color: #333;
            --light-text: #6c757d;
            --border-color: #dee2e6;
            --bg-light: #f8f9fa;
            --card-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s ease;
        }

        .oder {
            text-align: center;
            margin: 40px 0 30px;
            font-size: 2.8rem;
            color: var(--primary-color);
            font-weight: 700;
            position: relative;
            padding-bottom: 20px;
            animation: fadeInUp 0.8s ease;
        }

        .oder:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-color), var(--primary-hover));
            border-radius: 2px;
        }

        .chekount {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            margin-bottom: 40px;
            animation: slideInUp 0.8s ease;
        }

        .ored_adres,
        .adres_label {
            background: white;
            padding: 30px;
            border-radius: 20px;
            box-shadow: var(--card-shadow);
            position: relative;
            overflow: hidden;
        }

        .ored_adres:before,
        .adres_label:before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(90deg, var(--primary-color), var(--primary-hover));
        }

        .ored_adres h2,
        .adres_label h2 {
            color: var(--primary-color);
            font-size: 1.8rem;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid var(--bg-light);
            position: relative;
        }

        .ored_adres h2:after,
        .adres_label h2:after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 60px;
            height: 2px;
            background: var(--primary-color);
        }

        .label_chekount {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .label_adres {
            display: grid;
            grid-template-columns: 1fr;
            gap: 0;
        }

        .chekount label {
            font-weight: 600;
            color: var(--text-color);
            margin-bottom: 8px;
            display: block;
            font-size: 1rem;
        }

        .chekount input[type="text"],
        .chekount input[type="email"],
        .chekount input[type="tel"],
        .chekount select {
            width: 100%;
            padding: 15px 20px;
            border: 2px solid var(--border-color);
            border-radius: 12px;
            font-size: 1rem;
            transition: var(--transition);
            background-color: var(--bg-light);
            margin-bottom: 20px;
        }

        .chekount input:focus,
        .chekount select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(74, 107, 255, 0.2);
            outline: none;
            background-color: white;
            transform: translateY(-2px);
        }

        .chekount select {
            appearance: none;
            background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 15px center;
            background-size: 16px;
        }

        .your_oder {
            background: white;
            padding: 30px;
            border-radius: 20px;
            box-shadow: var(--card-shadow);
            margin-bottom: 30px;
            animation: slideInUp 1s ease;
        }

        .oder_item {
            display: grid;
            grid-template-columns: 100px 1fr auto;
            gap: 20px;
            padding: 20px;
            border: 2px solid var(--bg-light);
            border-radius: 15px;
            margin-bottom: 15px;
            transition: var(--transition);
            align-items: center;
        }

        .oder_item:hover {
            border-color: var(--primary-color);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(74, 107, 255, 0.1);
        }

        .oder_item img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 10px;
            border: 2px solid var(--bg-light);
        }

        .order_name {
            font-weight: 600;
            color: var(--text-color);
            font-size: 1.1rem;
            margin-bottom: 5px;
        }

        .order_code {
            color: var(--light-text);
            font-size: 0.9rem;
            margin-bottom: 5px;
        }

        .order_quantity {
            color: var(--text-color);
            font-weight: 500;
        }

        .order_price {
            text-align: right;
            font-weight: 600;
            color: var(--primary-color);
            font-size: 1.1rem;
        }

        .order_price span {
            color: var(--light-text);
            font-size: 0.9rem;
        }

        .oder_total {
            text-align: center;
            font-size: 1.5rem;
            color: var(--text-color);
            margin: 30px 0;
            font-weight: 600;
        }

        .oder_total b {
            color: var(--primary-color);
            font-size: 2rem;
        }

        .order_ready {
            text-align: center;
            margin: 40px 0;
        }

        .order_ready_button {
            background: #4CAF50;
            color: white;
            border: none;
            padding: 20px 40px;
            border-radius: 50px;
            font-size: 1.3rem;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            position: relative;
            overflow: hidden;
        }

        .order_ready_button:before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: #4CAF50;
            transition: var(--transition);
        }

        .order_ready_button:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 40px rgba(74, 107, 255, 0.4);
        }

        .order_ready_button:hover:before {
            left: 100%;
        }

        .checkout-progress {
            display: flex;
            justify-content: center;
            margin: 30px 0;
            position: relative;
        }

        .progress-step {
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
            z-index: 2;
            padding: 0 30px;
        }

        .progress-step.active .step-icon {
            background: var(--primary-color);
            color: white;
            transform: scale(1.1);
        }

        .progress-step.active .step-label {
            color: var(--primary-color);
            font-weight: 600;
        }

        .step-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: var(--bg-light);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            color: var(--light-text);
            margin-bottom: 10px;
            transition: var(--transition);
            border: 2px solid var(--border-color);
        }

        .step-label {
            color: var(--light-text);
            font-size: 0.9rem;
            text-align: center;
            transition: var(--transition);
        }

        .progress-line {
            position: absolute;
            top: 25px;
            left: 0;
            right: 0;
            height: 2px;
            background: var(--border-color);
            z-index: 1;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(50px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 968px) {
            .chekount {
                grid-template-columns: 1fr;
                gap: 30px;
            }

            .label_chekount {
                grid-template-columns: 1fr;
                gap: 0;
            }

            .oder_item {
                grid-template-columns: 80px 1fr;
                gap: 15px;
            }

            .order_price {
                text-align: left;
                grid-column: 1 / -1;
                padding-top: 10px;
                border-top: 1px solid var(--bg-light);
            }
        }

        @media (max-width: 768px) {
            .oder {
                font-size: 2.2rem;
            }

            .ored_adres,
            .adres_label {
                padding: 20px 15px;
            }

            .checkout-progress {
                flex-direction: column;
                gap: 20px;
            }

            .progress-line {
                display: none;
            }

            .order_ready_button {
                padding: 15px 30px;
                font-size: 1.1rem;
            }
        }

        @media (max-width: 480px) {
            .oder {
                font-size: 1.8rem;
            }

            .oder_item {
                grid-template-columns: 1fr;
                text-align: center;
            }

            .oder_item img {
                margin: 0 auto;
            }

            .oder_total {
                font-size: 1.2rem;
            }

            .oder_total b {
                font-size: 1.5rem;
            }
        }

        .security-badge {
            text-align: center;
            margin: 20px 0;
            padding: 15px;
            background: var(--bg-light);
            border-radius: 10px;
            border-left: 4px solid var(--success-color);
        }

        .security-badge i {
            color: var(--success-color);
            font-size: 1.5rem;
            margin-right: 10px;
        }

        .input-with-icon {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 15px;
            top: 27px;
            transform: translateY(-50%);
            color: var(--light-text);
            z-index: 2;
        }

        .input-with-icon input {
            padding-left: 45px !important;
        }
    </style>
</head>

<body>
    <?php include("components/header.php"); ?>

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
    <div class="block">
        <div class="oder">Оформлення замовлення</div>
    </div>
    <div class="checkout-progress">
        <div class="progress-line"></div>
        <div class="progress-step active">
            <div class="step-icon">
                <i class="fas fa-shopping-cart"></i>
            </div>
            <div class="step-label">Кошик</div>
        </div>
        <div class="progress-step active">
            <div class="step-icon">
                <i class="fas fa-user"></i>
            </div>
            <div class="step-label">Дані</div>
        </div>
        <div class="progress-step">
            <div class="step-icon">
                <i class="fas fa-truck"></i>
            </div>
            <div class="step-label">Доставка</div>
        </div>
        <div class="progress-step">
            <div class="step-icon">
                <i class="fas fa-credit-card"></i>
            </div>
            <div class="step-label">Оплата</div>
        </div>
    </div>

    <div class="block">
        <form action="odercheck.php" method="POST">
            <div class="chekount">
                <div class="ored_adres">
                    <h2><i class="fas fa-user" style="margin-right: 10px;"></i>Покупець</h2>
                    <div class="label_chekount">
                        <div>
                            <div class="input-with-icon">
                                <i class="fas fa-user input-icon"></i>
                                <input type="text" id="firstName" name="firstName" placeholder="Ім'я" required
                                    value="<?= htmlspecialchars($user_row['firstName'] ?? '') ?>">
                            </div>

                            <div class="input-with-icon">
                                <i class="fas fa-user-tag input-icon"></i>
                                <input type="text" id="lastName" name="lastName" placeholder="Прізвище" required
                                    value="<?= htmlspecialchars($user_row['lastName'] ?? '') ?>">
                            </div>
                        </div>
                        <div>
                            <div class="input-with-icon">
                                <i class="fas fa-envelope input-icon"></i>
                                <input type="email" id="email" name="email" placeholder="E-Mail" required
                                    value="<?= htmlspecialchars($user_row['email'] ?? '') ?>">
                            </div>

                            <div class="input-with-icon">
                                <i class="fas fa-phone input-icon"></i>
                                <input type="tel" id="phone1" name="phone" placeholder="+380XXXXXXXXX" required
                                    value="<?= htmlspecialchars($user_row['phone'] ?? '') ?>">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="adres_label">
                    <h2><i class="fas fa-map-marker-alt" style="margin-right: 10px;"></i>Адреса доставки</h2>
                    <div class="label_adres">
                        <div class="input-with-icon">
                            <i class="fas fa-city input-icon"></i>
                            <input type="text" id="city" name="city" placeholder="Місто" required>
                        </div>

                        <div class="input-with-icon">
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
                        </div>

                        <div class="input-with-icon">
                            <i class="fas fa-home input-icon"></i>
                            <input type="text" id="address" name="address" placeholder="Адреса (необов'язково)">
                        </div>

                        <div class="input-with-icon">
                            <i class="fas fa-store input-icon"></i>
                            <input type="text" id="nova_poshta" name="nova_poshta"
                                placeholder="Номер відділення Нової Пошти (необов'язково)">
                        </div>
                    </div>
                </div>
            </div>
            <h3 style="color: var(--primary-color); margin-bottom: 20px; text-align: center;">
                <i class="fas fa-shopping-bag" style="margin-right: 10px;"></i>Ваше замовлення (<?= $total_items ?>
                товарів)
            </h3>
            <div class="your_oder">


                <?php foreach ($basket_items as $item): ?>
                    <div class="oder_item">
                        <img src="<?= $item['img'] ?>" alt="<?= $item['name'] ?>">
                        <div>
                            <p class="order_name"><?= $item['name'] ?></p>
                            <p class="order_code">Код: <?= $item['productCode'] ?></p>
                            <p class="order_quantity">Кількість: <?= $item['quantity'] ?> шт.</p>
                        </div>
                        <p class="order_price">
                            <?php if ($item['has_discount']): ?>
                                <span style="text-decoration: line-through; color: #999;">
                                    <?= number_format($item['original_price'] * $item['quantity'], 2) ?> ₴
                                </span><br>
                            <?php endif; ?>
                            <?= number_format($item['total'], 2) ?> ₴
                        </p>
                    </div>
                <?php endforeach; ?>
            </div>

            <p class="oder_total">Загальна сума: <b><?= number_format($total, 2) ?> ₴</b></p>

            <div class="security-badge">
                <i class="fas fa-shield-alt"></i>
                <span>Безпечна оплата • Захист даних • Гарантія якості</span>
            </div>

            <div class="order_ready">
                <input type="hidden" name="total_amount" value="<?= $total ?>">
                <input type="hidden" name="total_items" value="<?= $total_items ?>">
                <button type="submit" class="order_ready_button">
                    <i class="fas fa-check-circle" style="margin-right: 10px;"></i>
                    Підтвердити замовлення
                </button>
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