<?php
include('data/session_start.php');
include('data/database.php');
include('data/discounts.php');
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
include('data/baner2.php');
include('data/category.php');
$isLoggedIn = isset($_SESSION['user_id']) && $_SESSION['user_id'] > 0;
$userSale = 0;

if ($isLoggedIn) {
    $user_id = $_SESSION['user_id'];
    $user_sql = "SELECT * FROM users WHERE id = ?";
    $user_stmt = $db_conn->prepare($user_sql);
    $user_stmt->bind_param("i", $user_id);
    $user_stmt->execute();
    $user_result = $user_stmt->get_result();
    $user_row = $user_result->fetch_assoc();
    $user_stmt->close();

    $userSale = $user_row['sale'] ?? 0;
    if ($userSale == 0) {
        $userSale = 10;
    }
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
$max_page = 70;
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
$current_page = $page_active + 1;

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

$count_sql = "SELECT COUNT(*) as total FROM `products` $category_active $search_active";
$count_result = $db_conn->query($count_sql);
if ($count_result) {
    $total_data = $count_result->fetch_assoc();
    $total_products = $total_data['total'];
} else {
    $total_products = 0;
}
$total_pages = ceil($total_products / $max_page);
if ($total_pages > 0 && $page_active >= $total_pages) {
    $page_active = $total_pages - 1;
    header("Location: index.php?page=$page_active$category_get_t$search_get_t$sort_get_t");
    exit;
}

$db_sql = "SELECT * FROM `products` $category_active $search_active $sort_active LIMIT $max_page OFFSET $offset";
$tabl = $db_conn->query($db_sql);
if (!$tabl->num_rows && $page_active > 0) {
    $next_page_t = $page_active - 1;
    header("Location: index.php?page=$next_page_t$category_get_t$search_get_t$sort_get_t");
    exit;
}
function getCategoryName($category_id)
{
    global $db_conn;
    $sql = "SELECT name FROM categories WHERE id = ?";
    $stmt = $db_conn->prepare($sql);
    $stmt->bind_param("i", $category_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $category = $result->fetch_assoc();
    $stmt->close();
    return $category ? $category['name'] : '';
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
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">
    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title>Інтернет-Магазин КанцКроп</title>
</head>
<style>
    :root {
        --slider-primary: #4CAF50;
        --slider-primary-hover: #1b5a1d;
        --slider-bg: #f8f9fa;
        --slider-text: #333;
        --slider-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
    }

    .slick_slider .slick-dots {
        bottom: 20px;
    }

    .slick_slider .slick-dots li {
        width: 12px;
        height: 12px;
        margin: 0 6px;
    }

    .slick_slider .slick-dots li button {
        width: 12px;
        height: 12px;
        padding: 0;
    }

    .slick_slider .slick-dots li button:before {
        content: '';
        width: 12px;
        height: 12px;
        background: black;
        border-radius: 50%;
        opacity: 0.6;
        transition: all 0.3s ease;
    }

    .slick_slider .slick-dots li.slick-active button:before {
        background: var(--slider-primary);
        opacity: 1;
        transform: scale(1.2);
    }

    .slick_slider .slick-dots li button:hover:before {
        opacity: 1;
        transform: scale(1.3);
    }

    .slick_slider .slick-loading .slick-list {
        background: var(--slider-bg) url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="50" cy="50" r="40" fill="none" stroke="%234CAF50" stroke-width="8" stroke-dasharray="62.83 62.83" transform="rotate(0 50 50)"><animateTransform attributeName="transform" type="rotate" repeatCount="indefinite" dur="1s" values="0 50 50;360 50 50"/></circle></svg>') center center no-repeat;
        background-size: 50px;
    }
</style>

<body>
    <?php include("components/header.php") ?>
    <div class="whatWeHave unselectable">
        <div class="block">
            <div class="whatWeHave_kans">
                <img src="categoty/school-material.png" alt="Канцелярские товары">
                <div class="categories">
                    <button class="categories-button" onclick="toggleCategories(this)">
                        <p class="category-p">Категорії</p>
                        <?php echo $category_get ? ': ' . htmlspecialchars(getCategoryName($category_get)) : ''; ?>
                    </button>
                    <div class="categories-menu">
                        <?php
                        $category_query->data_seek(0);
                        while ($category_row = $category_query->fetch_assoc()): ?>
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
    <div class="block">
        <div class="sort-block">
            <div class="sort-container">
                <label class="sort-label">Сортування товарів</label>
                <form method="GET" id="sortForm">
                    <div class="select-wrapper">
                        <select name="sort" onchange="this.form.submit()">
                            <option value="default">За замовчуванням</option>
                            <option value="price_asc" <?= $sort_get == 'price_asc' ? 'selected' : '' ?>>За ціною
                                (зростання)
                            </option>
                            <option value="price_desc" <?= $sort_get == 'price_desc' ? 'selected' : '' ?>>За ціною
                                (спадання)
                            </option>
                        </select>
                    </div>
                    <?php if ($category_get): ?>
                        <input type="hidden" name="category" value="<?= htmlspecialchars($category_get) ?>">
                    <?php endif; ?>
                    <?php if ($search_get): ?>
                        <input type="hidden" name="search" value="<?= htmlspecialchars($search_get) ?>">
                    <?php endif; ?>
                </form>
                <p class="form-note">Зміни зберігаються автоматично</p>
            </div>
        </div>
    </div>


    <div class="product_tile unselectable block">
        <?php if ($tabl->num_rows > 0): ?>
            <?php while ($row = $tabl->fetch_assoc()): ?>
                <?php
                $original_price = $row['price'];
                $modifier = $row['price_modifier'] ?? 0;
                $base_price = $original_price * (1 + $modifier / 100);
                $discount_price = $base_price;
                $has_discount = false;

                if ($isLoggedIn && $userSale > 0) {
                    $discount_price = $base_price * (1 - $userSale / 100);
                    $has_discount = true;
                }
                ?>

                <div class="product">
                    <a href="product.php?id=<?= $row['id'] ?>" class="product_link">
                        <img class="mini_img" src="<?= $row['img'] ?>" alt="<?= htmlspecialchars($row['name']) ?>">
                        <div class="product_info"><?= htmlspecialchars($row['name']) ?></div>
                    </a>
                    <div class="product_manufacturer">
                        <p>Код: <?php print_r($row["productСode"]) ?></p>
                    </div>
                    <div class="price_buy">
                        <div class="price-container">
                            <?php if ($has_discount): ?>
                                <span class="old-price"><?= number_format($base_price, 2) ?>₴</span>
                            <?php endif; ?>
                            <span class="new-price <?= $has_discount ? 'discounted' : '' ?>">
                                <?= number_format($discount_price, 2) ?>₴
                            </span>

                            <?php if ($has_discount && $userSale > 0): ?>
                                <small style="color: green; font-size: 12px;">Ваша знижка: <?= $userSale ?>%</small>
                            <?php endif; ?>
                        </div>
                        <?php
                        $product_id = $row['id'];
                        $isInCart = in_array($product_id, array_column($basket_items, 'id'));
                        ?>
                        <button class="buy-btn <?= $isInCart ? 'in-cart' : '' ?>"
                            onclick="addToCart(<?= $product_id ?>, event)">
                            <?php if ($isInCart): ?>
                                <i class="fa-solid fa-check"></i> У кошику
                            <?php else: ?>
                                <i class="fa-solid fa-cart-plus"></i> Купити
                            <?php endif; ?>
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

    <?php if ($tabl->num_rows > 0 && $total_pages > 1): ?>
        <div class="pagination">
            <?php
            if ($current_page > 1): ?>
                <a href="index.php?page=<?= $page_active - 1 ?><?= $category_get_t . $sort_get_t . $search_get_t ?>">
                    <i class="fa-solid fa-chevron-left"></i>
                </a>
            <?php endif; ?>

            <?php
            $start_page = max(1, $current_page - 2);
            $end_page = min($total_pages, $start_page + 4);

            if ($end_page - $start_page < 4) {
                $start_page = max(1, $end_page - 4);
            }
            ?>

            <?php
            if ($start_page > 1): ?>
                <a href="index.php?page=0<?= $category_get_t . $sort_get_t . $search_get_t ?>">1</a>
                <?php if ($start_page > 2): ?>
                    <span class="pagination-ellipsis">...</span>
                <?php endif; ?>
            <?php endif; ?>

            <?php for ($i = $start_page; $i <= $end_page; $i++): ?>
                <a href="index.php?page=<?= $i - 1 ?><?= $category_get_t . $sort_get_t . $search_get_t ?>"
                    class="<?= $i == $current_page ? 'active' : '' ?>">
                    <?= $i ?>
                </a>
            <?php endfor; ?>

            <?php
            if ($end_page < $total_pages): ?>
                <?php if ($end_page < $total_pages - 1): ?>
                    <span class="pagination-ellipsis">...</span>
                <?php endif; ?>
                <a href="index.php?page=<?= $total_pages - 1 ?><?= $category_get_t . $sort_get_t . $search_get_t ?>">
                    <?= $total_pages ?>
                </a>
            <?php endif; ?>

            <?php
            if ($current_page < $total_pages): ?>
                <a href="index.php?page=<?= $page_active + 1 ?><?= $category_get_t . $sort_get_t . $search_get_t ?>">
                    <i class="fa-solid fa-chevron-right"></i>
                </a>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <div class="benefits-section unselectable">
        <div class="block">
            <div class="benefits-header">
                <h2>Наші переваги</h2>
                <p>Чому клієнти обирають нас</p>
                <div class="benefits-divider"></div>
            </div>
            <div class="benefits-grid">
                <?php foreach ($data_baner1 as $value): ?>
                    <div class="benefit-card">
                        <div class="benefit-icon">
                            <img src="<?= $value['img'] ?>" alt="<?= htmlspecialchars($value['name']) ?>">
                            <div class="icon-overlay"></div>
                        </div>
                        <div class="benefit-content">
                            <h3><?= htmlspecialchars($value['name']) ?></h3>
                            <p><?= htmlspecialchars($value['text']) ?></p>
                        </div>
                        <div class="benefit-hover-effect"></div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <div class="logo-map-section unselectable">
        <div class="block">
            <div class="logo-map-grid">
                <div class="logo-card">
                    <div class="logo-content">
                        <img src="img/kanskrop_logo.png" alt="KansKrop" class="main-logo">
                        <div class="logo-overlay"></div>
                        <div class="logo-glow"></div>
                    </div>
                    <div class="logo-info">
                        <h3>КанцКроп</h3>
                        <p>Інтернет-магазин канцелярських товарів</p>
                        <div class="logo-features">
                            <span class="feature-tag">Якість</span>
                            <span class="feature-tag">Надійність</span>
                            <span class="feature-tag">Швидка доставка</span>
                        </div>
                    </div>
                </div>
                <div class="map-card">
                    <div class="map-header">
                        <h3>Ми знаходимось</h3>
                        <div class="map-divider"></div>
                    </div>
                    <div class="map-container">
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m10!1m8!1m3!1d195.92550423792767!2d32.28413667954254!3d48.51912323100282!3m2!1i1024!2i768!4f13.1!5e1!3m2!1suk!2sua!4v1756587377844!5m2!1suk!2sua"
                            width="100%" height="300" style="border: none;" referrerpolicy="no-referrer-when-downgrade"
                            class="map-iframe" loading="lazy" allowfullscreen>
                        </iframe>
                        <div class="map-overlay"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
    include("contact/contact_end.php")
        ?>

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
                autoplaySpeed: 4000,
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
    <div id="logoutModal" class="logout-modal" style="display: none;">
        <div class="logout-modal-content">
            <h3>Ви впевнені, що хочете вийти?</h3>
            <div class="logout-modal-buttons">
                <button onclick="performLogout()" class="logout-btn confirm">Так, вийти</button>
                <button onclick="closeLogoutModal()" class="logout-btn cancel">Скасувати</button>
            </div>
        </div>
    </div>
</body>

</html>