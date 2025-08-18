<?php
include('data/database.php');
include('data/baner.php');
include('data/baner2.php');
include('data/user_data.php');

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/shop.css?">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Open+Sans:ital,wght@0,300..800;1,300..800&family=Poiret+One&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
    <script src="https://kit.fontawesome.com/ee9963f31c.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="css/adaptive.css?">
    <title>Авторизація</title>
</head>

<body>
    <div class="head unselectable">
        <div class="block">
            <a class="logo" href="index.php"><img src="img/kanskrop_logo.png" alt=""></a>
            <form class="input_head">
                <label>
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" placeholder="Я шукаю..." name="search">
                    <button><i class="fa-solid fa-magnifying-glass"></i></button>
                </label>
            </form>
            <div class="icons_head">
                <?php
                if ($user_query->num_rows > 0) {
                    include("dropdown.php")
                        ?>
                    <button onclick="toggleMenu()"><i class="fa-regular fa-user"></i></button>
                    <?php
                } else { ?>
                    <button><?php include("auth.php"); ?></button>
                    <?php
                }
                ?>
                <button onclick="openCart()"><i class="fa-solid fa-cart-shopping"></i></button>
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
        <div>

        </div>
        <div class="form-section">
            <h2>Постійний покупець</h2>
            <form action="authcheck.php">
                <label for="email">* E-Mail адреса</label>
                <input type="email" id="email" name="email" placeholder="E-Mail адреса">
                <br>
                <label for="password">* Пароль</label>
                <input type="password" id="password" name="password" placeholder="Пароль">
                <?php
                if (isset($_SESSION['login_error'])) {
                    echo "<p class='p_login_error'>" . $_SESSION['login_error'] . "</p>";
                    unset($_SESSION['login_error']);
                } ?>
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
                    src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d554.1606144377334!2d32.284208611360036!3d48.519159446434855!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x40d05d0008bb3049%3A0x75b540cf193b012!2z0JrQsNC90YbQmtGA0L7QvyAvINCa0LDQvdGH0YLQvtCy0LDRgNC4!5e1!3m2!1suk!2snl!4v1754843009070!5m2!1suk!2snl"
                    width="450" height="300" style="border-radius: 15px; border-color:lightgray;"
                    allowfullscreen=""></iframe>
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
    <style>

    </style>
    <?php
    include('productBasket.php');
    include("dropdown.php");
    ?>
    <script src="js/main.js"></script>
</body>

</html>