<?php
include('data/session_start.php');
include('data/database.php');
include('productBasket.php');

// Подсчет корзины (как в accountinfo.php)
$isLoggedIn = isset($_SESSION['user_id']) && $_SESSION['user_id'] > 0;
$cart_count = 0;
if ($isLoggedIn) {
    $user_id = $_SESSION['user_id'];
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
?>
<!DOCTYPE html>
<html lang="uk">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <link rel="canonical" href="https://www.kanskrop.com/terms">
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
    <title>Умови користувача | KansKrop</title>
    <style>
        /* Стили из accountinfo.php */
        :root {
            --primary-color: #4CAF50;
            --primary-hover: #1b5a1dff;
            --secondary-color: #6c757d;
            --success-color: #28a745;
            --text-color: #333;
            --light-text: #6c757d;
            --border-color: #dee2e6;
            --bg-light: #f8f9fa;
            --card-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s ease;
        }

        .h2_info {
            text-align: center;
            margin: 40px 0 30px;
        }

        .h2_info h2 {
            font-size: 2.8rem;
            color: var(--primary-color);
            font-weight: 700;
            position: relative;
            padding-bottom: 20px;
            margin-bottom: 30px;
            animation: fadeInUp 0.8s ease;
        }

        .h2_info h2:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 4px;
            background: var(--primary-color);
            border-radius: 2px;
        }

        .terms_content {
            max-width: 1000px;
            margin: 0 auto 60px;
            animation: slideInUp 0.8s ease;
        }

        .terms-text {
            background: white;
            padding: 40px;
            border-radius: 20px;
            box-shadow: var(--card-shadow);
            position: relative;
            overflow: hidden;
        }

        .terms-text:before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: var(--primary-color);
        }

        .h3_info {
            color: var(--primary-color);
            font-size: 1.8rem;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid var(--bg-light);
            position: relative;
        }

        .h3_info:after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 60px;
            height: 2px;
            background: var(--primary-color);
        }

        .terms-text p,
        .terms-text ul {
            margin-bottom: 20px;
            line-height: 1.6;
            color: var(--text-color);
        }

        .terms-text ul {
            padding-left: 20px;
        }

        .terms-text li {
            margin-bottom: 10px;
            position: relative;
        }

        .terms-text li:before {
            content: '•';
            color: var(--primary-color);
            font-weight: bold;
            display: inline-block;
            width: 1em;
            margin-left: -1em;
        }

        .highlight {
            background-color: rgba(76, 175, 80, 0.1);
            padding: 2px 5px;
            border-radius: 3px;
            font-weight: 600;
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

        @media (max-width: 768px) {
            .h2_info h2 {
                font-size: 2.2rem;
            }

            .terms-text {
                padding: 30px 20px;
            }
        }

        @media (max-width: 480px) {
            .h2_info h2 {
                font-size: 1.8rem;
            }
        }

        .back-button {
            display: inline-block;
            margin-top: 30px;
            padding: 12px 25px;
            background-color: var(--primary-color);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            transition: var(--transition);
        }

        .back-button:hover {
            background-color: var(--primary-hover);
            transform: translateY(-2px);
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

    <div class="h2_info">
        <div class="block">
            <h2>Умови користувача</h2>
        </div>
    </div>

    <div class="terms_content block">
        <div class="terms-text">
            <h3 class="h3_info">Угода користувача інтернет-магазину KansKrop</h3>

            <p>Ця Угода користувача (далі – «Угода») регулює відносини між Інтернет-магазином «KansKrop» (далі –
                «Магазин») та користувачем веб-сайту <span class="highlight">https://www.kanskrop.com/</span> (далі –
                «Користувач»).</p>

            <h3 class="h3_info">1. Загальні положення</h3>
            <p>1.1. Користуючись сайтом Магазину та оформлюючи замовлення, Користувач підтверджує, що повністю
                погоджується з умовами цієї Угоди та Політикою конфіденційності.</p>
            <p>1.2. Магазин залишає за собою право вносити зміни до цієї Угоди. Зміни набувають чинності з моменту їх
                розміщення на сайті.</p>

            <h3 class="h3_info">2. Визначення</h3>
            <ul>
                <li><strong>«Товар»</strong> – канцелярські та офісні товари, представлені в каталозі Магазину.</li>
                <li><strong>«Замовлення»</strong> – оформлена Користувачем заявка на покупку обраних Товарів.</li>
                <li><strong>«Особисті дані»</strong> – будь-яка інформація, що relates to ідентифікованої або
                    ідентифікованої фізичної особи.</li>
            </ul>

            <h3 class="h3_info">3. Оформлення та оплата замовлення</h3>
            <p>3.1. Користувач може оформляти замовлення як після реєстрації на сайті, так і без неї (як гість).</p>
            <p>3.2. Ціни на Товари вказуються у гривнях. Магазин залишає за собою право змінювати ціни.</p>
            <p>3.3. Оплата здійснюється згідно з способами, запропонованими на сайті (онлайн-оплата, оплата при
                отриманні тощо).</p>

            <h3 class="h3_info">4. Доставка та повернення</h3>
            <p>4.1. Умови та вартість доставки описані в окремому розділі сайту «Доставка і оплата».</p>
            <p>4.2. Повернення та обмін Товару належної якості здійснюється згідно із Законом України «Про захист прав
                споживачів».</p>

            <h3 class="h3_info">5. Інтелектуальна власність</h3>
            <p>5.1. Всі тексти, графіка, дизайн, логотип «KansKrop» є об'єктами інтелектуальної власності Магазину.
                Будь-яке копіювання матеріалів сайту без дозволу заборонено.</p>

            <h3 class="h3_info">6. Відповідальність</h3>
            <p>6.1. Магазин не несе відповідальності за неправдиву інформацію, надану Користувачем під час оформлення
                замовлення.</p>
            <p>6.2. Магазин намагається забезпечити точність описів та зображень Товарів, але можливі незначні
                розбіжності.</p>

            <h3 class="h3_info">7. Конфіденційність</h3>
            <p>7.1. Магазин збирає та обробляє Особисті дані Користувача відповідно до Політики конфіденційності, яка є
                невід'ємною частиною цієї Угоди.</p>

            <p>Дата останнього оновлення: <?php echo date('d.m.Y'); ?></p>

            <a href="javascript:history.back()" class="back-button">
                <i class="fas fa-arrow-left"></i> Повернутися назад
            </a>
        </div>
    </div>

    <!-- Нижні блоки (benefits, logo-map, контакти) як в accountinfo.php -->
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

    <?php
    if ($isLoggedIn) {
        include("dropdown.php");
    }
    ?>
    <script src="js/main.js"></script>
</body>

</html>