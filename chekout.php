<?php
include('data/database.php');
include('data/baner2.php');
include('data/category.php');
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
    <link rel="stylesheet" type="text/css" href="./slick/slick.css">
    <link rel="stylesheet" type="text/css" href="./slick/slick-theme.css">
    <script src="https://code.jquery.com/jquery-2.2.0.min.js" type="text/javascript"></script>
    <script src="./slick/slick.js" type="text/javascript" charset="utf-8"></script>
    <script src="https://kit.fontawesome.com/ee9963f31c.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="css/adaptive.css?">
    <title>Оформлення заказу</title>
</head>

<body>
    <div class="head unselectable">
        <div class="block">
            <a class="logo" href="index.php"><img src="img/kanskrop_logo.png" alt=""></a>
            <form method="GET" class="input_head" action="index.php">
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
                <div class="categories">
                    <a href="index.php"> <button class="categories-button"
                            onclick="toggleCategories(this)">Категорії</button></a>
                </div>
                <div class="marquee">
                    <span id="marqueeText"></span>
                </div>
            </div>
        </div>
    </div>
    <div class="oder block">Оформлення замовлення.</div>
    <div class="block">

        <div class="chekount">
            <div class="ored_adres">
                <h2>Покупець</h2>
                <div class="label_chekount">
                    <form action="odercheck.php" method="get">
                        <div>
                            <label for="firstName">* Ім’я</label><br>
                            <input type="text" id="firstName" name="firstName" placeholder="Ім’я">
                            <br><br>
                            <label for="lastName">* Прізвище</label><br>
                            <input type="text" id="lastName" name="lastName" placeholder="Прізвище">
                        </div>
                        <div>
                            <label for="email">* E-Mail</label><br>
                            <input type="email" id="email" name="email" placeholder="E-Mail">
                            <br><br>
                            <label for="phone">* Телефон</label><br>
                            <input type="tel" id="phone" name="phone" placeholder="Телефон">
                        </div>
                    </form>
                </div>
            </div>
            <div class="your_oder">
                <div>
                    <img src="product_img/dcc99ac50efe11e9beb9002682d30847_fc1d810e999b11ee83fe002522f8e32e-1000x1000.webp"
                        alt="">
                </div>

                <div class="name_price">
                    <p class="ored_name">asfgjhaf</p>
                    <p class="oder_price">321 ₴</p>
                </div>

            </div>
        </div>
        <div class="adres">
            <div class="adres_label">
                <h2>Адреса доставки</h2>
                <div class="label_adres">
                    <div>
                        <label for="city">* Місто</label><br>
                        <input type="text" id="city" name="city" placeholder="Місто">
                        <br><br>
                        <label for="region">* Регіон / Область</label><br>
                        <input type="text" id="region" name="region" placeholder="Регіон / Область">
                        <br><br>
                        <label for="adres">* Адреса</label><br>
                        <input type="text" id="adres" name="adres" placeholder="Адреса">
                    </div>
                </div>
            </div>
            <div class="order_ready">
                <a href="odercheck.php"><button class="order_ready_button">Оформлення замовлення </button></a>
            </div>
        </div>
    </div>
    <div class="banner-blocks-container2">
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
            <img src="img/kanskrop_logo.png" alt="">
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
                <a href="https://t.me/"><img src="contact/telegram.png" alt="" class="contact_logo">
                    <p>Telegram</p>
                </a>
                <a href=""><img src="contact/viber.png" alt="" class="contact_logo">
                    <p>Viber</p>
                </a>
            </div>
        </div>
    </div>
    <?php
    include('productBasket.php');
    include("dropdown.php");
    ?>
</body>

</html>