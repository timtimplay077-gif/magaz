<?php
include('data/database.php');
include('data/baner.php');
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
    <title>Інтернет-Магазин КанцКроп</title>
</head>

<body>
    <?php
    $max_page = 75;
    $search_get = $_GET['search'] ?? '';
    $search_get_t = "&search=$search_get";
    $search_active = '';
    $page_active = $_GET['page'] ?? 0;
    $category_get = $_GET['category'] ?? '';
    $category_get_t = "&category=$category_get";
    $category_active = '';
    $category_sql = "SELECT *  FROM `categories`";
    $category_query = $db_conn->query($category_sql);
    $offset = isset($page_active) ? $page_active * $max_page : 0;
    $sort_get = $_GET['sort'] ?? '';
    $sort_active = "";
    $sort_get_t = "&sort=$sort_get";
    if ($category_get) {
        $category_active = " WHERE category=$category_get";
    }
    if ($search_get) {
        if ($category_get) {
            $search_active = " AND `name` LIKE '%$search_get%'";
        } else {
            $search_active = " WHERE `name` LIKE '%$search_get%'";
        }
    }

    if ($sort_get == 'price_asc') {
        $sort_active = " ORDER BY `products`.`price` ASC";
    } else if ($sort_get == 'price_desc') {
        $sort_active = " ORDER BY `products`.`price` DESC";
    }
    $db_sql = "SELECT *  FROM `products` $category_active $search_active $sort_active  LIMIT $max_page OFFSET $offset";

    $tabl = $db_conn->query($db_sql);
    if (!$tabl->num_rows && $page_active > 0) {
        $next_page_t = $page_active - 1;
        header("Location: index.php?page=$next_page_t$category_get_t$search_get_t$sort_get_t");
    }
    $order_by = "";

    if (isset($_GET['sort'])) {
        switch ($_GET['sort']) {
            case 'price_asc':
                $order_by = "ORDER BY price ASC";
                break;
            case 'price_desc':
                $order_by = "ORDER BY price DESC";
                break;
            default:
                $order_by = "";
        }
    }
    if (!isset($_SESSION['user_id'])) {

        header("Location: login.php");
        exit;
    }
    $user_id = $_SESSION['user_id'];
    ?>
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
                    <button class="categories-button" onclick="toggleCategories(this)">Категорії</button>
                    <div class="categories-menu">
                        <?php
                        for ($i = 0; $i < $category_query->num_rows; $i++) {
                            $category_row = $category_query->fetch_assoc();
                            ?>
                            <a href="index.php?category=<?= $category_row['id'] ?>" alt=""
                                class="category_img"><?= $category_row['name'] ?></a>

                        <?php } ?>
                    </div>
                </div>

                <div class="text-slider">
                    <div class="marquee">
                        <span id="marqueeText"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="slider_wrapper block">
        <?php include 'components/slider.php'; ?>
    </div>
    <div class="sort block">
        <div>
            <form method="GET" id="sortForm">
                <select name="sort" onchange="document.getElementById('sortForm').submit();">
                    <option value="default">
                        За замовчуванням</option>
                    <option value="price_asc" <?php if ($sort_get == 'price_asc')
                        echo 'selected'; ?>>За ціною
                        (зростання)</option>
                    <option value="price_desc" <?php if ($sort_get == 'price_desc')
                        echo 'selected'; ?>>За ціною
                        (спадання)</option>
                </select>
                <?php if (isset($_GET['category'])): ?>
                    <input type="hidden" name="category" value="<?php echo htmlspecialchars($_GET['category']); ?>">
                <?php endif; ?>
            </form>
        </div>
    </div>
    </div>

    <div class="tovars unselectable">

        <div class="block">
            <div class="tovars_ul">

            </div>
        </div>
    </div>
    <div>
        <div class="product_tile unselectable block">

            <?php
            for ($i = 0; $i < $tabl->num_rows; $i++) {
                $row = $tabl->fetch_assoc();
                $original_price = $row['price'];

                if (isset($user_row['sale']) && $user_row['sale'] > 0) {
                    $final_price = $original_price * (1 - $user_row['sale'] / 100);
                } else {
                    $final_price = $original_price;
                }
                ?>

                <div class="product">
                    <a href="product.php?id=<?php echo $row['id']; ?>" class="product_link">
                        <img class="mini_img" src="<?php echo $row['img']; ?>" alt="">
                        <div class="product_info">
                            <?php echo $row['name']; ?>
                        </div>
                    </a>
                    <div class="price_buy">
                        <p class="price" style="color: rgba(0, 0, 0, 1); font-weight: bold;">
                            <?php echo round($final_price, 2); ?>₴
                        </p>
                        <a href="addCart.php?user_id=<?php echo $user_id; ?>&product_id=<?php echo $row['id']; ?>">
                            <img src="contact/shopping-bag.png" alt="" class="buy_button">
                        </a>
                    </div>

                </div>

                <?php
            }
            ?>

        </div>
        <div class="pagination">
            <?php
            $prew_page = $page_active > 0 ? $page_active - 1 : 'null';
            $next_page = $tabl->num_rows > 1 ? $page_active + 1 : 'null';
            if ($prew_page != 'null') { ?>
                <a href="index.php?page=<?= $prew_page ?><?= $category_get_t . $sort_get_t . $search_get_t ?>"><i
                        class="fa-solid fa-chevron-left"></i></a>

            <?php }
            if ($next_page != 'null') { ?>
                <a href="index.php?page=<?= $next_page ?><?= $category_get_t . $sort_get_t . $search_get_t ?>"><i
                        class="fa-solid fa-chevron-right"></i></a>

            <?php } ?>



        </div>
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
    <script src="js/main.js"></script>
    <script>
        $(document).on('ready', function () {
            $(".slick_slider").slick({
                dots: true,
                infinite: true,
                slidesToShow: 1,
                centerMode: true,
                variableWidth: true,
                autoplay: true,
                autoplaySpeed: 5000,
                speed: 800,
                pauseOnHover: true,
                pauseOnFocus: true,

            });
        })
    </script>
    <?php
    include('productBasket.php');
    include("dropdown.php");
    ?>


</body>

</html>