<?php
//ЭТОТ ФАЙЛ НЕ РАБОТАЕТ ЕСЛИ ЧТО
session_start();
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
    <link rel="canonical" href="https://www.example.com/">
    <link rel="stylesheet" href="css/shop.css?">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Open+Sans:ital,wght@0,300..800;1,300..800&family=Poiret+One&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
    <script src="https://kit.fontawesome.com/ee9963f31c.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="css/adaptive.css?">
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">
    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
    <title>Створення товару</title>
</head>

<body>
    <?php include("components/header.php");
    ?>
    <div class="whatWeHave unselectable">
        <div class="block">
            <div class="category-header">
                <div class="category-card">
                    <div class="category-icon">
                        <img src="categoty/school-material.png" alt="Канцелярія">
                        <div class="icon-hover-effect"></div>
                        <div class="icon-glow"></div>
                    </div>
                    <a href="index.php" class="category-link">
                        <span class="link-text">Канцелярія</span>
                        <div class="link-underline"></div>
                        <div class="link-hover-effect"></div>
                    </a>
                </div>
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

    <?php
    if ($isLoggedIn) {
        include("dropdown.php");
    }
    ?>
    <script src="js/main.js"></script>
</body>

</html>