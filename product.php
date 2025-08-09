<?php
include("data/database.php");
include('data/baner.php');
include('data/baner2.php');
include('data/category.php');
include('data/user_data.php');
$id = $_GET['id'];
$db_sql = "SELECT * FROM products WHERE id = '$id'";
$tabl = $db_conn->query($db_sql);
$row = $tabl->fetch_assoc();
function dd($data): void
{
}
$db_imgage_sql = "SELECT * FROM `productimages` WHERE `product_Id` = $id";
$db_imgage_query = $db_conn->query($db_imgage_sql);

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
    <title><?php print_r($row["name"]) ?></title>
</head>

<body>
    <?php

    ?>
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
    <div class="about_product unselectable">
        <div class="block">
            <a href="#Jac">Усе про товар</a>
            <a href="#Jac">Характеристики</a>
        </div>
    </div>
    <div class="block unselectable">
        <h3 class="product_name"><?php print_r($row["name"]) ?></h3>
    </div>

    <div class="product_row unselectable">
        <div class="block">
            <div class="slider_wrapper2">
                <div class="arrow_l1" onclick="slider_product('left')">
                    <i class="fa-solid fa-chevron-left"></i>
                </div>
                <div class="arrow_r1" onclick="slider_product('right')">
                    <i class="fa-solid fa-chevron-right"></i>
                </div>
                <img class="slider_product" src="<?php print_r($row["img"]) ?>" alt="">
                <div class="product_photo_slider">
                    <?php
                    $images = "";
                    for ($i = 0; $i < $db_imgage_query->num_rows; $i++) {
                        $db_imgage_row = $db_imgage_query->fetch_assoc();
                        $images .= "'" . $db_imgage_row["img"] . "',";
                        $img = $db_imgage_row["img"];
                        ?>
                        <img onclick="set_mimiImg('<?= $i ?>')" src="<?php print_r($img) ?>" alt="">
                    <?php } ?>
                </div>
                <script>
                    const slider_wrapper_product = [<?= $images ?>];
                </script>
            </div>
            <div class="product_row_about">
                <div class="status">
                    <p class="stock_status">В наявності</p>
                </div>
                <div class="product_manufacturer">

                    <p>Код: <?php print_r($row["productСode"]) ?></p>
                </div>
                <?php
                $original_price = $row['price'];
                $discount_price = $original_price;

                if (isset($_SESSION['id'])) {
                    $user_id = $_SESSION['id'];
                    $user_sql = "SELECT sale FROM users WHERE id = '$user_id'";
                    $user_result = $db_conn->query($user_sql);
                    if ($user_result && $user_result->num_rows > 0) {
                        $user_row = $user_result->fetch_assoc();
                        if ($user_row['sale'] > 0) {
                            $discount_price = $original_price * (1 - $user_row['sale'] / 100);
                        }
                    }
                }
                ?>
                <div class="product_row_price">
                    <div class="price">
                        <?= round(num: $discount_price, precision: 2) ?> ₴
                    </div>
                </div>

                <div class="product_row_about_buy">
                    <a href="addcart.php?user_id=<?= $user_id ?>&product_id=<?= $row['id'] ?>">
                        <img src="contact/shopping-bag.png" alt="" class="product_row_about_buy">Купить
                    </a>
                </div>

            </div>
        </div>
    </div>
    <div class="product_delivery_payment unselectable">
        <div class="block">
            <div class="delivery">
                <h2>Доставка</h2>
                <a href="https://novaposhta.ua/shipping-cost/"><img src="payment/novaposhta.svg" alt="">Доставка Новою
                    Поштою від 70 ₴</a>
                <p class="delivery_p">Адреси найближчих відділень дивитися на карті</p>
                <a href="https://www.ukrposhta.ua/ua/taryfy-ukrposhta-standart" class="delivery_a"><img
                        src="payment/ukrposhta.svg" alt="">
                    <p class="delivery_p1">Доставка Укр поштою від 35 ₴</p>
                </a>

                <p class="p_last"><img src="payment/pickup.svg" alt="">Самовивіз (м.Кропивницький) Безкоштовно</p>
            </div>
            <div class="payment">
                <h2>Оплата</h2>
                <div class="paymant_photo">
                    <img src="payment/1.svg" alt="">
                    <img src="payment/2.svg" alt="">
                    <img src="payment/4.svg" alt="">
                    <img src="payment/5.svg" alt="">
                </div>


            </div>
        </div>
    </div>
    <div class="product_description unselectable">
        <div class="block" id="Jac">
            <h2 class="product_name">Усе про <?php print_r($row["name"]) ?></h2>
            <p><?php print_r($row["ABOUTBPRODUCT"]) ?></p>
        </div>
    </div>
    <div class="block">
        <div class="characteristics">
            <h2>Характеристика <?php print_r($row["name"]) ?></h2>
            <p><?php print_r($row["characteristic_1"]) ?></p>
            <p><?php print_r($row["characteristic_2"]) ?></p>
            <p><?php print_r($row["characteristic_3"]) ?></p>
            <p><?php print_r($row["characteristic_4"]) ?></p>
            <p><?php print_r($row["characteristic_5"]) ?></p>
            <p><?php print_r($row["characteristic_6"]) ?></p>
            <p><?php print_r($row["characteristic_7"]) ?></p>
            <p><?php print_r($row["characteristic_8"]) ?></p>
            <p><?php print_r($row["characteristic_9"]) ?></p>
            <p><?php print_r($row["characteristic_10"]) ?></p>
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
            <img src="img/kanskrop_logo.png" alt="">
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
    <script src="js/main.js"></script>
    <?php
    include('productBasket.php');
    include("dropdown.php");
    ?>

</body>

</html>