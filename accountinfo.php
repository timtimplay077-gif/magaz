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
include('data/baner2.php');
include('data/user_data.php');
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
    <title>Обліковий запис</title>
    <style>
        :root {
            --primary-color: #4CAF50;
            --primary-hover: #1b5a1dff;
            --secondary-color: #6c757d;
            --success-color: #28a745;
            --text-color: #333;
            --light-text: #6c757d;
            --border-color: #dee2e6;
            --bg-light: #f8f9fa;
            --card-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s ease;

        }

        .h2_info {
            text-align: center;
            margin: 40px 0 30px;
        }

        .h2_info h2 {
            font-size: 2.8rem;
            color: var(--primary-color);
            font-weight: 700;
            position: relative;
            padding-bottom: 20px;
            margin-bottom: 30px;
            animation: fadeInUp 0.8s ease;
        }

        .h2_info h2:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-color), var(--primary-hover));
            border-radius: 2px;
        }

        .accountinfo_content {
            max-width: 1000px;
            margin: 0 auto 60px;
            display: grid;
            grid-template-columns: 1fr 300px;
            gap: 40px;
            animation: slideInUp 0.8s ease;
        }

        .accountinfo {
            background: white;
            padding: 40px;
            border-radius: 20px;
            box-shadow: var(--card-shadow);
            position: relative;
            overflow: hidden;
        }

        .accountinfo:before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(90deg, var(--primary-color), var(--primary-hover));
        }

        .h3_info {
            color: var(--primary-color);
            font-size: 1.8rem;
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 2px solid var(--bg-light);
            position: relative;
        }

        .h3_info:after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 60px;
            height: 2px;
            background: var(--primary-color);
        }

        .accountinfo label {
            font-weight: 600;
            color: var(--text-color);
            margin-bottom: 8px;
            display: block;
            font-size: 1rem;
        }

        .accountinfo input[type="text"],
        .accountinfo input[type="email"],
        .accountinfo input[type="tel"] {
            width: 100%;
            padding: 15px 20px;
            border: 2px solid var(--border-color);
            border-radius: 12px;
            font-size: 1rem;
            transition: var(--transition);
            background-color: var(--bg-light);
            margin-bottom: 20px;
        }

        .accountinfo input:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(74, 107, 255, 0.2);
            outline: none;
            background-color: white;
            transform: translateY(-2px);
        }

        .accountinfo input:read-only {
            background-color: #f8f9fa;
            color: var(--light-text);
            cursor: not-allowed;
        }

        .user-avatar {
            text-align: center;
            margin-bottom: 30px;
        }

        .user-avatar i {
            font-size: 4rem;
            color: var(--primary-color);
            background: linear-gradient(135deg, var(--bg-light), #e9ecef);
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
            border: 3px solid var(--primary-color);
            box-shadow: 0 5px 15px rgba(74, 107, 255, 0.2);
        }

        .user-welcome {
            text-align: center;
            margin-bottom: 30px;
        }

        .user-welcome h4 {
            font-size: 1.5rem;
            color: var(--text-color);
            margin-bottom: 5px;
        }

        .user-welcome p {
            color: var(--light-text);
            font-size: 1.1rem;
        }

        .logaut-box {
            background: white;
            padding: 30px;
            border-radius: 20px;
            box-shadow: var(--card-shadow);
            height: fit-content;
            position: sticky;
            top: 100px;
        }

        .logaut-box h3 {
            color: var(--primary-color);
            font-size: 1.5rem;
            margin-bottom: 25px;
            text-align: center;
        }

        .logaut-box button {
            width: 100%;
            padding: 15px 20px;
            border: none;
            border-radius: 12px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .logaut-box button:first-child {
            background: var(--primary-color);
            color: white;
        }

        .logaut-box button:first-child:hover {
            background: var(--primary-hover);
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(74, 107, 255, 0.3);
        }

        .logaut-box button:last-child {
            background: #4CAF50;
            color: white;
        }

        .logaut-box button:last-child:hover {
            background: #c82333;
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(220, 53, 69, 0.3);
        }

        .account-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 40px;
            padding-top: 30px;
            border-top: 2px solid var(--bg-light);
        }

        .stat-card {
            background: linear-gradient(135deg, var(--bg-light), #e9ecef);
            padding: 20px;
            border-radius: 15px;
            text-align: center;
            transition: var(--transition);
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .stat-icon {
            font-size: 2rem;
            color: var(--primary-color);
            margin-bottom: 15px;
        }

        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-color);
            margin-bottom: 5px;
        }

        .stat-label {
            color: var(--light-text);
            font-size: 0.9rem;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(50px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 968px) {
            .accountinfo_content {
                grid-template-columns: 1fr;
                gap: 30px;
            }

            .logaut-box {
                position: relative;
                top: 0;
            }
        }

        @media (max-width: 768px) {
            .h2_info h2 {
                font-size: 2.2rem;
            }

            .accountinfo {
                padding: 30px 20px;
            }

            .account-stats {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 480px) {
            .h2_info h2 {
                font-size: 1.8rem;
            }

            .accountinfo input[type="text"],
            .accountinfo input[type="email"],
            .accountinfo input[type="tel"] {
                padding: 12px 15px;
            }

            .user-avatar i {
                font-size: 3rem;
            }
        }

        .input-group {
            position: relative;
            margin-bottom: 25px;
        }

        .input-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
        }

        .input-icon {
            position: absolute;
            left: 15px;
            top: 28px;
            transform: translateY(-50%);
            color: var(--light-text);
        }

        .input-with-icon {
            padding-left: 45px !important;
        }

        .edit-btn {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--primary-color);
            cursor: pointer;
            font-size: 1.1rem;
            transition: var(--transition);
        }

        .edit-btn:hover {
            color: var(--primary-hover);
            transform: translateY(-50%) scale(1.1);
        }
    </style>
</head>

<body>
    <?php include("components/header.php"); ?>

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

    <div class="h2_info">
        <div class="block">
            <h2>Обліковий запис</h2>
        </div>

    </div>

    <?php
    $result = $db_conn->query("SELECT * FROM users WHERE id = $user_id");
    if ($roww = $result->fetch_assoc()) {
        $firstName = htmlspecialchars($roww['firstName']);
        $lastName = htmlspecialchars($roww['lastName']);
        $email = htmlspecialchars($roww['email']);
        $phone = htmlspecialchars($roww['phone']);
    } else {
        $firstName = "";
        $lastName = "";
        $email = "";
        $phone = "";
    }
    ?>

    <div class="accountinfo_content block">
        <div class="accountinfo">
            <div class="user-avatar">
                <i class="fas fa-user-circle"></i>
            </div>

            <div class="user-welcome">
                <h4>Вітаємо, <?php echo $firstName . ' ' . $lastName; ?>!</h4>
                <p>Ваші особисті дані</p>
            </div>

            <h3 class="h3_info">Особисті дані</h3>

            <div class="input-group">
                <label for="firstName">Ім'я</label>
                <div style="position: relative;">
                    <i class="fas fa-user input-icon"></i>
                    <input type="text" id="firstName" name="firstName" class="input-with-icon" placeholder="Ім'я"
                        value="<?php echo $firstName; ?>" readonly>
                </div>
            </div>

            <div class="input-group">
                <label for="lastName">Прізвище</label>
                <div style="position: relative;">
                    <i class="fas fa-user-tag input-icon"></i>
                    <input type="text" id="lastName" name="lastName" class="input-with-icon" placeholder="Прізвище"
                        value="<?php echo $lastName; ?>" readonly>
                </div>
            </div>

            <div class="input-group">
                <label for="email">E-Mail</label>
                <div style="position: relative;">
                    <i class="fas fa-envelope input-icon"></i>
                    <input type="email" id="email" name="email" class="input-with-icon" placeholder="E-Mail"
                        value="<?php echo $email; ?>" readonly>
                </div>
            </div>

            <div class="input-group">
                <label for="phone">Телефон</label>
                <div style="position: relative;">
                    <i class="fas fa-phone input-icon"></i>
                    <input type="tel" id="phone1" name="phone" class="input-with-icon" placeholder="Телефон"
                        value="<?php echo $phone; ?>" readonly>
                </div>
            </div>
        </div>

        <div class="logaut-box">
            <h3>Керування акаунтом</h3>
            <a href="accountinfo.php">
                <button>
                    <i class="fas fa-user-cog"></i>
                    Налаштування
                </button>
            </a>
            <button onclick="confirmLogout()">
                <i class="fas fa-sign-out-alt"></i>
                Вийти
            </button>
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