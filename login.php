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
include('data/baner.php');
include('data/baner2.php');
include('data/user_data.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <link rel="canonical" href="https://www.example.com/">
    <link rel="stylesheet" href="css/shop.css">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&family=Open+Sans:wght@300..800&family=Poiret+One&family=Roboto:wght@100..900&display=swap"
        rel="stylesheet">
    <script src="https://kit.fontawesome.com/ee9963f31c.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="css/adaptive.css">
    <title>Авторизація</title>
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
                <img src="categoty/school-material.png" alt="">
                <a href="index.php">Канцелярія</a>
            </div>
            <div class="marquee">
                <span id="marqueeText"></span>
            </div>
        </div>
    </div>
    <div class="h2_login block">
        <h2>Авторизація</h2>
    </div>
    <div class="container block unselectable">
        <div class="form-section">
            <h2>Постійний покупець</h2>
            <form action="authcheck.php" method="POST">
                <label for="email">* E-Mail адреса</label>
                <input type="email" id="email" name="email" placeholder="E-Mail адреса" required>
                <br>
                <label for="password">* Пароль</label>
                <input type="password" id="password" name="password" placeholder="Пароль" required>
                <?php
                if (isset($_SESSION['login_error'])) {
                    echo "<p class='p_login_error'>" . $_SESSION['login_error'] . "</p>";
                    unset($_SESSION['login_error']);
                }
                ?>
                <a class="link" href="reset-password.php">Забули пароль?</a>
                <button class="btn" type="submit">Увійти</button>
            </form>
        </div>

        <div class="form-section unselectable">
            <h2>Новий покупець</h2>
            <p>
                Створення облікового запису допоможе здійснювати покупки швидше та більш зручно.
                Ви також зможете відслідковувати статус замовлень, використовувати закладки,
                переглядати минулі замовлення, та отримувати знижки для постійних покупців.
            </p>
            <a href="registration.php"><button class="btn">Продовжити</button></a>
        </div>
    </div>

    <div class="banner-blocks-container2 unselectable">
        <div class="block">
            <?php foreach ($data_baner1 as $key => $value): ?>
                <div class="card2">
                    <img src="<?= $value['img'] ?>" alt="" class="logo_card">
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
                    src="https://www.google.com/maps/embed?pb=!1m10!1m8!1m3!1d195.92550423792767!2d32.28413667954254!3d48.51912323100282!3m2!1i1024!2i768!4f13.1!5e1!3m2!1suk!2sua!4v1756587377844!5m2!1suk!2sua"
                    width="450" height="300" style="border-radius: 15px; border: 1px solid lightgray;"
                    referrerpolicy="no-referrer-when-downgrade"></iframe>
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
                <a href="https://t.me/kanskrop" target="_blank">
                    <img src="contact/telegram.png" alt="Telegram" class="contact_logo">
                    <p>Telegram</p>
                </a>
                <a href="viber://chat?number=%2B380500534408">
                    <img src="contact/viber.png" alt="Viber" class="contact_logo">
                    <p>Viber</p>
                </a>
            </div>
        </div>
    </div>

    <div id="authCheck" data-logged-in="<?php echo $isLoggedIn ? 'true' : 'false'; ?>" style="display: none;"></div>
    <?php

    if ($isLoggedIn) {
        include("dropdown.php");
    }
    ?>
    <script src="js/main.js"></script>
</body>

</html>