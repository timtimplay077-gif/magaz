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
$max_page = 75;
$search_get = $_GET['search'] ?? '';
$search_get_t = "&search=$search_get";
$search_active = '';
$page_active = $_GET['page'] ?? 0;
$category_get = $_GET['category'] ?? '';
$category_get_t = "&category=$category_get";
$category_active = '';
$sort_get = $_GET['sort'] ?? '';
$sort_active = "";
$sort_get_t = "&sort=$sort_get";
$category_sql = "SELECT * FROM `categories`";
$category_query = $db_conn->query($category_sql);
$offset = isset($page_active) ? $page_active * $max_page : 0;

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

$db_sql = "SELECT * FROM `products` $category_active $search_active $sort_active LIMIT $max_page OFFSET $offset";
$tabl = $db_conn->query($db_sql);
if (!$tabl->num_rows && $page_active > 0) {
    $next_page_t = $page_active - 1;
    header("Location: index.php?page=$next_page_t$category_get_t$search_get_t$sort_get_t");
    exit;
}
?>

<!DOCTYPE html>
<html lang="uk">

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
    <div class="head unselectable">
        <div class="block">
            <a class="logo" href="index.php"><img src="img/kanskrop_logo.png" alt="KansKrop"></a>
            <form method="GET" class="input_head" action="index.php">
                <label>
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" placeholder="Я шукаю..." name="search"
                        value="<?= htmlspecialchars($search_get) ?>">
                    <button type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
                </label>
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
                <img src="categoty/school-material.png" alt="Канцелярские товары">
                <div class="categories">
                    <button class="categories-button" onclick="toggleCategories(this)">Категорії</button>
                    <div class="categories-menu">
                        <?php while ($category_row = $category_query->fetch_assoc()): ?>
                            <a href="index.php?category=<?= $category_row['id'] ?>" class="category_link">
                                <?= htmlspecialchars($category_row['name']) ?>
                            </a>
                        <?php endwhile; ?>
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
                <select name="sort" onchange="this.form.submit()">
                    <option value="default">За замовчуванням</option>
                    <option value="price_asc" <?= $sort_get == 'price_asc' ? 'selected' : '' ?>>За ціною (зростання)
                    </option>
                    <option value="price_desc" <?= $sort_get == 'price_desc' ? 'selected' : '' ?>>За ціною (спадання)
                    </option>
                </select>
                <?php if ($category_get): ?>
                    <input type="hidden" name="category" value="<?= htmlspecialchars($category_get) ?>">
                <?php endif; ?>
                <?php if ($search_get): ?>
                    <input type="hidden" name="search" value="<?= htmlspecialchars($search_get) ?>">
                <?php endif; ?>
            </form>
        </div>
    </div>

    <div class="product_tile unselectable block">
        <?php if ($tabl->num_rows > 0): ?>
            <?php while ($row = $tabl->fetch_assoc()): ?>
                <?php
                $price = $row['price'];
                if (!empty($row['price_modifier']))
                    $price *= (1 + $row['price_modifier'] / 100);
                if ($isLoggedIn && !empty($user_row['sale']))
                    $price *= (1 - $user_row['sale'] / 100);
                ?>
                <div class="product">
                    <a href="product.php?id=<?= $row['id'] ?>" class="product_link">
                        <img class="mini_img" src="<?= $row['img'] ?>" alt="<?= htmlspecialchars($row['name']) ?>">
                        <div class="product_info"><?= htmlspecialchars($row['name']) ?></div>
                    </a>
                    <div class="price_buy">
                        <p class="price"><?= number_format($price, 2) ?>₴</p>
                        <?php
                        $product_id = $row['id'];
                        $isInCart = in_array($product_id, array_column($basket_items, 'id'));
                        ?>
                        <button class="buy-btn <?= $isInCart ? 'in-cart' : '' ?>"
                            onclick="addToCart(<?= $product_id ?>, event)">
                            <?= $isInCart ? 'У кошику' : 'Купити' ?>
                        </button>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="no-products">
                <p>Товарів не знайдено</p>
            </div>
        <?php endif; ?>
    </div>

    <?php if ($tabl->num_rows > 0): ?>
        <div class="pagination">
            <?php
            $prev_page = $page_active > 0 ? $page_active - 1 : null;
            $next_page = $tabl->num_rows >= $max_page ? $page_active + 1 : null;
            ?>
            <?php if ($prev_page !== null): ?>
                <a href="index.php?page=<?= $prev_page ?><?= $category_get_t . $sort_get_t . $search_get_t ?>">
                    <i class="fa-solid fa-chevron-left"></i>
                </a>
            <?php endif; ?>

            <?php if ($next_page !== null): ?>
                <a href="index.php?page=<?= $next_page ?><?= $category_get_t . $sort_get_t . $search_get_t ?>">
                    <i class="fa-solid fa-chevron-right"></i>
                </a>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <div class="banner-blocks-container2">
        <div class="block">
            <?php foreach ($data_baner1 as $value): ?>
                <div class="card2">
                    <img src="<?= $value['img'] ?>" alt="<?= htmlspecialchars($value['name']) ?>" class="logo_card">
                    <h3><?= htmlspecialchars($value['name']) ?></h3>
                    <p><?= htmlspecialchars($value['text']) ?></p>
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

    <script src="js/main.js"></script>
    <script>
        $(document).ready(function () {
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
        });
    </script>
    <?php
    if ($isLoggedIn) {
        include("dropdown.php");
    }
    ?>
</body>

</html>