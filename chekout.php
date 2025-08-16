<?php
include('data/database.php');
include('data/baner2.php');
include('data/category.php');
include('data/user_data.php');
$user_id = $_SESSION['user_id'];
$basket_sql = "SELECT * FROM basket WHERE user_id = '$user_id'";
$basket_query = $db_conn->query($basket_sql);
$basket_product_id = [];
while ($basket_row = $basket_query->fetch_assoc()) {
    $basket_product_id[] = $basket_row['product_id'];
}
if (!empty($basket_product_id)) {
    $in = implode(',', array_map('intval', $basket_product_id));
    $basket_product = "SELECT * FROM products WHERE id IN ($in)";
    $basket_product_query = $db_conn->query($basket_product);
}
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
                <h2>Ваше замовлення</h2>
                <?php if (!empty($basket_product_query) && $basket_product_query->num_rows > 0) {
                    $total = 0;
                    while ($item = $basket_product_query->fetch_assoc()) {
                        $total += $item['price'];
                        ?>
                        <div class="oder_item">
                            <img src="<?php echo $item['img']; ?>" alt="<?php echo $item['name']; ?>" width="120">
                            <p class="oder_price"><?php echo $item['price']; ?>₴</p>
                        </div>
                    <?php } ?>
                    <p class="oder_total"><b>Загальна сума: <?php echo $total; ?>₴</b></p>
                <?php } else { ?>
                    <p>Кошик порожній.</p>
                <?php } ?>
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
    <?php
    include('productBasket.php');
    include("dropdown.php");
    ?>
</body>

</html>