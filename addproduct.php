<?php
session_start();
include('data/baner.php');
include('data/baner2.php');
include('data/user_data.php');
include('productBasket.php');
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
    <title>Створення товару</title>
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
    <div class="block">
        <h2 class="add_h2">Створення товару</h2>
    </div>
    <div class="block">
        <div class="register_product">
            <form action="addproductcheck.php" enctype="multipart/form-data">
                <label for="name">* Назва товару</label><br>
                <input type="text" id="name" name="name" placeholder="Назва товару">
                <br><br>
                <label for="categoty">* Категорія товару</label><br>
                <input type="text" id="category" name="category" placeholder="Категорія товару">
                <br><br>
                <label for="price">* Ціна товару</label><br>
                <input type="text" id="price" name="price" placeholder="Ціна товару">
                <br><br>
                <label for="manufacter">* Виробник</label><br>
                <input type="text" id="manufacter" name="manufacter" placeholder="Виробник">
                <br><br>
                <label for="code">* Код товару</label><br>
                <input type="text" id="code" name="code" placeholder="Код товару">
                <br><br>
                <label for="aboutProduct">* Опис товару</label><br>
                <textarea type="text" id="aboutProduct" name="aboutProduct" placeholder="Опис товару"></textarea>
                <br><br>
                <label for="photo">* Фото товару</label><br>
                <input type="file" id="photo" name="photo" placeholder="Фото товару">
                <br><br>
            </form>


            <div class="add_product">
                <button>Додати</button>
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
    if ($isLoggedIn) {
        include("dropdown.php");
    }
    ?>
    <script src="js/main.js"></script>
</body>

</html>