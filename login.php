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
            <a class="logo" href="index.php"><img src="img/KropKants_Logo_with_DarkBG.svg" alt=""></a>
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
                <a href="index.php">Канселярія</a>
            </div>
            <a href="">Інше</a>
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
            <form action="authcheck.php" method="post">
                <label for="email">* E-Mail адреса</label>
                <br></br>
                <input type="email" id="email" name="email" placeholder="E-Mail адреса">
                <br>
                <label for="password">* Пароль</label>
                <br></br>
                <input type="password" id="password" name="password" placeholder="Пароль">

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
            <img src="img/KropKants_Logo_with_DarkBG.svg" alt="">
        </div>
    </div>
    <div class="contact unselectable">
        <div class="block">
            <div class="card3">
                <p><img src="contact/phone.png" alt="" class="baner2_img">Номер телефона</p>
                <p><img src="contact/gmail.png" alt="" class="baner2_img">Наша пошта:</p>
                <p><img src="contact/location.png" alt="" class="baner2_img">м.Кропивницький</p>
            </div>
            <div class="ourVT">
                <a href="https://t.me/"><img src="contact/telegram.png" alt="" class="contact_logo">
                    <p>Telegram</p>
                </a>
                <a href=""><img src="contact/viber.png" alt="" class="contact_logo">
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