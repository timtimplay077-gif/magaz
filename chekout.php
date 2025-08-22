<?php
include('data/database.php');
include('data/baner2.php');
include('data/category.php');
// if (!isset($_SESSION['user_id'])) {
//     header('Location: login.php?message=Для оформления заказа необходимо авторизоваться');
//     exit;
// }

$user_id = $_SESSION['user_id']; 
$user_sql = "SELECT sale FROM users WHERE id = '$user_id'";
$user_result = $db_conn->query($user_sql);
$user_row = $user_result->fetch_assoc();
$basket_items = [];

while ($basket_row = $basket_query->fetch_assoc()) {
    $basket_items[$basket_row['product_id']] = [
        'count' => $basket_row['count'],
        'productСode' => $basket_row['productСode']
    ];
}
$basket_product_query = null;
if (!empty($basket_items)) {
    $in = implode(',', array_map('intval', array_keys($basket_items)));
    $basket_product_sql = "SELECT * FROM products WHERE id IN ($in)";
    $basket_product_query = $db_conn->query($basket_product_sql);
}

$user_row = $db_conn->query("SELECT sale FROM users WHERE id = '$user_id'")->fetch_assoc();
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
                <?php
                if ($user_query->num_rows > 0) {
                    ?>
                    <button onclick="openCart()"><i class="fa-solid fa-cart-shopping"></i></button>
                    <?php
                } else {
                    ?>
                    <button onclick="alert('Спочатку авторизуйтесь!')"><i class="fa-solid fa-cart-shopping"></i></button>
                    <?php
                }
                ?>
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
        <form action="odercheck.php" method="get">
            <div class="chekount">
                <div class="ored_adres">
                    <h2>Покупець</h2>
                    <div class="label_chekount">
                        <div style=" margin-bottom: 30px;">
                            <label for=" firstName">* Ім'я</label><br>
                            <input type="text" id="firstName" name="firstName" placeholder="Ім'я" required>
                            <br><br>
                            <label for="lastName">* Прізвище</label><br>
                            <input type="text" id="lastName" name="lastName" placeholder="Прізвище" required>
                        </div>
                        <div>
                            <label for="email">* E-Mail</label><br>
                            <input type="email" id="email" name="email" placeholder="E-Mail" required>
                            <br><br>
                            <label for="phone">* Телефон</label><br>
                            <input type="tel" id="phone" name="phone" placeholder="Телефон" required>
                        </div>
                    </div>
                </div>

                <div class="adres_label">
                    <h2>Адреса доставки</h2>
                    <div class="label_adres">
                        <div>
                            <label for="city">* Місто</label><br>
                            <input type="text" id="city" name="city" placeholder="Місто" required>
                            <br><br>
                            <label for="region">* Регіон / Область</label><br>
                            <input type="text" id="region" name="region" placeholder="Регіон / Область" required>
                            <br><br>
                            <label for="adres">* Адреса</label><br>
                            <input type="text" id="adres" name="adres" placeholder="Адреса" required>
                        </div>
                    </div>
                </div>
            </div>
            <div class="your_oder">

                <?php
                $total = 0;
                $total_items = 0;

                if ($basket_product_query && $basket_product_query->num_rows > 0) {
                    while ($item = $basket_product_query->fetch_assoc()) {
                        $original_price = $item['price'];
                        if (isset($user_row['sale']) && $user_row['sale'] > 0) {
                            $final_price = $original_price * (1 - $user_row['sale'] / 100);
                        } else {
                            $final_price = $original_price;
                        }

                        $quantity = $basket_items[$item['id']]['count'];
                        $product_code = $basket_items[$item['id']]['productСode'];
                        $item_total = $final_price * $quantity;
                        $total += $item_total;
                        $total_items += $quantity;
                        ?>
                        <div class="oder_item">
                            <a href="product.php?id=<?php echo $item['id']; ?>">
                                <img src="<?php echo $item['img']; ?>" alt="<?php echo $item['name']; ?>">
                                <p class="order_name"><?php echo $item['name']; ?></p>
                                <p class="order_code">Код: <?php echo $product_code; ?></p>
                                <p class="order_quantity">Кількість: <?php echo $quantity; ?> шт.</p>
                                <p class="order_price"><?php echo number_format($final_price, 2); ?> ₴ ×
                                    <?php echo $quantity; ?> = <?php echo number_format($item_total, 2); ?> ₴
                                </p>
                            </a>
                        </div>
                        <?php
                    }
                } else {
                    echo '<p class="empty-cart">Кошик порожній</p>';
                }
                ?>
            </div>
            <p class="oder_total">Загальна сума: <b><?php echo number_format($total, 2); ?> ₴</b></p>
            <h3 class="oder_total">Ваше замовлення (<?php echo array_sum(array_column($basket_items, 'count')); ?>
                товарів)</h3>
            <div class="order_ready">
                <button type="submit" class="order_ready_button" <?php echo ($total_items == 0) ? 'disabled' : ''; ?>>Оформлення
                    замовлення</button>
            </div>
        </form>
    </div>

    <div class="banner-blocks-container2">
        <div class="block">
            <?php
            foreach ($data_baner1 as $key => $value) { ?>
                <div class="card2">
                    <img src="<?= $value['img'] ?>" alt="" class="logo_card">
                    <h3><?= $value['name'] ?></h3>
                    <p><?= $value['text'] ?></p>
                </div>
            <?php } ?>
        </div>
    </div>

    <div class="logo_end">
        <div class="block">
            <div>
                <img src="img/kanskrop_logo.png" alt="KansKrop">
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
                <p><img src="contact/phone.png" alt="" class="baner2_img">Номер телефона:⠀<span
                        class="phone_number">+380 500 534 408</span></p>
                <p><img src="contact/gmail.png" alt="" class="baner2_img">Наша пошта:⠀<span
                        class="phone_number">admin@kanskrop.com</span></p>
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