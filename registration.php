<?php
include('data/session_start.php');
include('data/database.php');
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
    <title>Реєстрація</title>
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
    <div class="registration_users_h2 block">
        <h2>Реєстрація</h2>
    </div>
    <div class="registration_users block">
        <div class="register_content unselectable">
            <h3>Контактні дані</h3>
            <form action="registercheck.php" method="POST">
                <div class="register_label">
                    <label for="firstName">* Ім’я</label><br>
                    <input type="text" id="firstName" name="firstName" placeholder="Ім’я"
                        value="<?= isset($_SESSION['get']['firstName']) ? $_SESSION['get']['firstName'] : '' ?>">
                    <?php
                    if (isset($_SESSION["errors"]['firstName'])) {
                        ?>
                        <p class="incorect_pass">Ім'я має містити від 1 до 32 символів</p>
                    <?php }
                    ?><br><br>

                    <label for="lastName">* Прізвище</label><br>
                    <input type="text" id="lastName" name="lastName" placeholder="Прізвище"
                        value="<?= isset($_SESSION['get']['lastName']) ? $_SESSION['get']['lastName'] : '' ?>">
                    <?php
                    if (isset($_SESSION["errors"]['lastName'])) {
                        ?>
                        <p class="incorect_pass">Прізвище має містити від 1 до 32 символів</p>
                    <?php }
                    ?><br><br>

                    <label for="email">* E-Mail</label><br>
                    <input type="email" id="email" name="email" placeholder="E-Mail"
                        value="<?= isset($_SESSION['get']['email']) ? $_SESSION['get']['email'] : '' ?>">
                    <?php
                    if (isset($_SESSION["errors"]['email'])) {
                        ?>
                        <p class="incorect_pass">E-mail адреса вказана невірно</p>
                    <?php }
                    ?><br><br>

                    <label for="phone">* Телефон</label><br>
                    <div class="phone-input-container">
                        <span class="phone-prefix">+380</span>
                        <input type="tel" id="phone" name="phone" placeholder="XXXXXXXXX"
                            value="<?= isset($_SESSION['get']['phone']) ? $_SESSION['get']['phone'] : '' ?>">
                    </div>
                    <?php
                    if (isset($_SESSION["errors"]['phone'])) {
                        ?>
                        <p class="incorect_pass">Номер телефону має містити 9 цифр після +380</p>
                    <?php }
                    ?>
                    <br><br>

                    <h3>Ваш пароль</h3>

                    <label for="password">* Пароль</label><br>
                    <input type="text" id="password" name="password" placeholder="Пароль"
                        value="<?= isset($_SESSION['get']['password']) ? $_SESSION['get']['password'] : '' ?>"><br><br>

                    <label for="confirmPassword">* Підтвердіть пароль</label><br>
                    <input type="text" id="confirmPassword" name="confirmPassword" placeholder="Підтвердіть пароль"
                        value="<?= isset($_SESSION['get']['confirmPassword']) ? $_SESSION['get']['confirmPassword'] : '' ?>"> <?php
                              if (isset($_SESSION["errors"]['password'])) {
                                  ?>
                        <p class="incorect_pass">Паролі не співпадають</p>
                    <?php }
                              ?><br><br>
                </div>
                <h3>Розсилка новин</h3>
                <label>Отримувати новини на E-Mail</label><br>
                <input type="radio" id="subscribeYes" name="newsletter" value="yes">
                <label for="subscribeYes">Так</label>
                <input type="radio" id="subscribeNo" name="newsletter" value="no" checked>
                <label for="subscribeNo">Ні</label><br><br>
                <input type="checkbox" id="terms" name="terms">
                <label for="terms">Я погоджуюсь з умовами <a href="#">Угода користувача</a></label><br><br>
                <button type="submit" class="register_button">Зареєструватися</button>
            </form>
        </div>
        <div>
            <div class="login-box">
                <a href="login.php"><button>Вхід</button></a>
                <a href="registration.php"><button>Реєстрація</button></a>
                <a href="reset-password.php"><button>Забули пароль?</button></a>
            </div>
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
    <?php
    include("dropdown.php");
    ?>
    <script src="js/main.js"></script>
</body>

</html>
<?php
$_SESSION['get'] = false;
$_SESSION['errors'] = false;
?>