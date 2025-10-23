<?php
include('data/session_start.php');
include('data/database.php');
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
    <title>Реєстрація</title>
    <style>
        :root {
            --primary-color: #4CAF50;
            --primary-hover: #1b5a1dff;
            --secondary-color: #f8f9fa;
            --text-color: #333;
            --light-text: #6c757d;
            --error-color: #dc3545;
            --success-color: #28a745;
            --border-color: #dee2e6;
            --border-radius: 12px;
            --box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            --transition: all 0.3s ease;
        }

        .registration_users_h2 {
            text-align: center;
            margin: 30px 0 20px;
        }

        .registration_users_h2 h2 {
            font-size: 2.5rem;
            color: var(--text-color);
            font-weight: 700;
            position: relative;
            padding-bottom: 15px;
            margin-bottom: 30px;
        }

        .registration_users_h2 h2:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 4px;
            background: var(--primary-color);
            border-radius: 2px;
        }

        .registration_users {
            display: flex;
            flex-wrap: wrap;
            gap: 40px;
            justify-content: center;
            margin: 30px auto 50px;
            max-width: 1200px;
        }

        .register_content {
            flex: 1;
            min-width: 500px;
            background: white;
            border-radius: var(--border-radius);
            padding: 40px;
            box-shadow: var(--box-shadow);
            transition: var(--transition);
            position: relative;
            overflow: hidden;
        }

        .register_content:before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: #4CAF50;
        }

        .register_content:hover {
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
            transform: translateY(-5px);
        }

        .register_content h3 {
            color: var(--primary-color);
            margin-bottom: 25px;
            font-size: 1.5rem;
            position: relative;
            padding-bottom: 15px;
            font-weight: 600;
        }

        .register_content h3:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 50px;
            height: 3px;
            background: var(--primary-color);
        }

        .register_label label {
            font-weight: 600;
            color: var(--text-color);
            margin-bottom: 8px;
            display: block;
            font-size: 1rem;
        }

        .register_label input[type="text"],
        .register_label input[type="email"],
        .register_label input[type="tel"],
        .register_label input[type="password"] {
            width: 100%;
            padding: 14px 16px;
            border: 1px solid var(--border-color);
            border-radius: var(--border-radius);
            font-size: 1rem;
            transition: var(--transition);
            box-sizing: border-box;
            background-color: #f8f9fa;
        }

        .register_label input:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(74, 107, 255, 0.2);
            outline: none;
            background-color: white;
        }

        .phone-input-container1 {
            position: relative;
            display: flex;
            align-items: center;
            margin-bottom: 5px;
        }

        .phone-prefix1 {
            background: #ebebeb;
            padding: 14px 12px;
            font-weight: 500;
            color: var(--text-color);
            height: 48px;
            display: flex;
            align-items: center;
        }

        #phone {
            border-radius: 0 var(--border-radius) var(--border-radius) 0;
            flex: 1;
            height: 48px;
        }

        .incorect_pass {
            color: var(--error-color);
            font-size: 0.85rem;
            margin-top: 5px;
            display: block;
        }

        .register_label input.error {
            border-color: var(--error-color);
        }

        .register_label input.success {
            border-color: var(--success-color);
        }

        .terms-container {
            display: flex;
            align-items: baseline;

            margin: 25px 0;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: var(--border-radius);
        }

        #terms {
            margin-right: 12px;
            margin-top: 4px;
            width: 18px;
            cursor: pointer;
        }

        .terms-container label {
            font-weight: normal;
            line-height: 1.5;
            color: var(--text-color);
            cursor: pointer;
        }

        .terms-container a {
            color: var(--primary-color);
            text-decoration: none;
            transition: var(--transition);
            font-weight: 600;
        }

        .terms-container a:hover {
            text-decoration: underline;
        }

        .register_button {
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 16px 30px;
            border-radius: var(--border-radius);
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            width: 100%;
            position: relative;
            overflow: hidden;
        }

        .register_button:before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: var(--transition);
        }

        .register_button:hover {
            background: var(--primary-hover);
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(74, 107, 255, 0.3);
        }

        .register_button:hover:before {
            left: 100%;
        }

        .login-box {
            background: white;
            border-radius: var(--border-radius);
            padding: 30px;
            box-shadow: var(--box-shadow);
            min-width: 280px;
            height: fit-content;
            position: relative;
            overflow: hidden;
        }

        .login-box:before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 5px;
            height: 100%;
            background: #4CAF50;
        }

        .login-box button {
            display: block;
            width: 100%;
            padding: 14px;
            margin-bottom: 15px;
            border: 2px solid var(--primary-color);
            border-radius: var(--border-radius);
            background: transparent;
            color: var(--primary-color);
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            text-align: center;
            text-decoration: none;
            font-size: 1rem;
        }

        .login-box button:hover {
            background: var(--primary-color);
            color: white;
            transform: translateY(-2px);
        }

        .login-box a {
            text-decoration: none;
        }

        .login-box a:last-child button {
            margin-bottom: 0;
            border-color: var(--light-text);
            color: var(--light-text);
        }

        .login-box a:last-child button:hover {
            background: var(--light-text);
            color: white;
            border-color: var(--light-text);
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .register_content,
        .login-box {
            animation: fadeIn 0.6s ease-out;
        }

        @media (max-width: 992px) {
            .registration_users {
                flex-direction: column;
                align-items: center;
            }

            .register_content {
                min-width: auto;
                width: 100%;
                max-width: 500px;
            }

            .login-box {
                width: 100%;
                max-width: 500px;
            }
        }

        @media (max-width: 576px) {
            .register_content {
                padding: 25px 20px;
            }

            .registration_users_h2 h2 {
                font-size: 2rem;
            }

            .register_content h3 {
                font-size: 1.3rem;
            }

            .register_label input[type="text"],
            .register_label input[type="email"],
            .register_label input[type="tel"],
            .register_label input[type="password"] {
                padding: 12px 14px;
            }
        }

        .form-section {
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid #f1f1f1;
        }

        .password-field {
            position: relative;
        }

        .toggle-password {
            position: absolute;
            right: 15px;
            top: 22px;
            transform: translateY(-50%);
            cursor: pointer;
            color: var(--light-text);
            background: none;
            border: none;
            font-size: 1.2rem;
            background: white;
        }

        input[type="checkbox"] {
            accent-color: var(--primary-color);
        }

        .validation-icon {
            position: absolute;
            right: 15px;
            top: 42px;
            transform: translateY(-50%);
            font-size: 1.2rem;
        }

        .valid .validation-icon {
            color: var(--success-color);
        }

        .invalid .validation-icon {
            color: var(--error-color);
        }

        .input-wrapper {
            position: relative;
            margin-bottom: 20px;
        }

        .register_content::after {
            content: '';
            position: absolute;
            bottom: 0;
            right: 0;
            width: 100px;
            height: 100px;
            background: radial-gradient(circle, rgba(74, 107, 255, 0.1) 0%, transparent 70%);
            z-index: -1;
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
    <div class="registration_users_h2 block">
        <h2>Реєстрація</h2>
    </div>

    <div class="registration_users block">
        <div class="register_content unselectable">
            <div class="form-section">
                <h3>Контактні дані</h3>
                <form action="registercheck.php" method="POST" id="registrationForm">
                    <div class="register_label">
                        <div class="input-wrapper">
                            <label for="firstName">* Ім’я</label>
                            <input type="text" id="firstName" name="firstName" placeholder="Введіть ваше ім'я"
                                value="<?= isset($_SESSION['get']['firstName']) ? $_SESSION['get']['firstName'] : '' ?>">
                            <?php if (isset($_SESSION["errors"]['firstName'])): ?>
                                <p class="incorect_pass">Ім'я має містити від 1 до 32 символів</p>
                            <?php endif; ?>
                        </div>

                        <div class="input-wrapper">
                            <label for="lastName">* Прізвище</label>
                            <input type="text" id="lastName" name="lastName" placeholder="Введіть ваше прізвище"
                                value="<?= isset($_SESSION['get']['lastName']) ? $_SESSION['get']['lastName'] : '' ?>">
                            <?php if (isset($_SESSION["errors"]['lastName'])): ?>
                                <p class="incorect_pass">Прізвище має містити від 1 до 32 символів</p>
                            <?php endif; ?>
                        </div>

                        <div class="input-wrapper">
                            <label for="email">* E-Mail</label>
                            <input type="email" id="email" name="email" placeholder="example@email.com"
                                value="<?= isset($_SESSION['get']['email']) ? $_SESSION['get']['email'] : '' ?>">
                            <?php if (isset($_SESSION["errors"]['email'])): ?>
                                <p class="incorect_pass">E-mail адреса вказана невірно</p>
                            <?php endif; ?>
                        </div>

                        <div class="input-wrapper">
                            <label for="phone">* Телефон</label>
                            <div class="phone-input-container1">
                                <span class="phone-prefix1">+380</span>
                                <input type="tel" id="phone" name="phone" placeholder="XXXXXXXXX"
                                    value="<?= isset($_SESSION['get']['phone']) ? $_SESSION['get']['phone'] : '' ?>">
                            </div>
                            <?php if (isset($_SESSION["errors"]['phone'])): ?>
                                <p class="incorect_pass">Номер телефону має містити 9 цифр після +380</p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="form-section">
                        <h3>Ваш пароль</h3>

                        <div class="input-wrapper">
                            <label for="password"
                                style="font-weight: 600; color: var(--text-color); margin-bottom: 8px; display: block; font-size: 1rem;">*
                                Пароль</label>
                            <div class="password-field">
                                <input type="password" id="password" name="password"
                                    placeholder="Створіть надійний пароль"
                                    value="<?= isset($_SESSION['get']['password']) ? $_SESSION['get']['password'] : '' ?>">
                                <button type="button" class="toggle-password" aria-label="Показати пароль">
                                    <i class="far fa-eye"></i>
                                </button>
                            </div>
                        </div>

                        <div class="input-wrapper">
                            <label for="confirmPassword"
                                style="font-weight: 600; color: var(--text-color); margin-bottom: 8px; display: block; font-size: 1rem;">*
                                Підтвердіть пароль</label>
                            <div class="password-field">
                                <input type="password" id="confirmPassword" name="confirmPassword"
                                    placeholder="Повторіть ваш пароль"
                                    value="<?= isset($_SESSION['get']['confirmPassword']) ? $_SESSION['get']['confirmPassword'] : '' ?>">
                                <button type="button" class="toggle-password" aria-label="Показати пароль">
                                    <i class="far fa-eye"></i>
                                </button>
                            </div>
                            <?php if (isset($_SESSION["errors"]['password'])): ?>
                                <p class="incorect_pass">Паролі не співпадають</p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="terms-container">
                        <input type="checkbox" id="terms" name="terms" required>
                        <label for="terms">Я погоджуюсь з <a href="terms.php">умовами угоди користувача</a> та політикою
                            конфіденційності</label>
                    </div>
                    <button type="submit" class="register_button"> <i class="fas fa-user-plus"
                            style="margin-right: 10px;"></i>Зареєструватися</button>
                </form>
            </div>
        </div>

        <div>
            <div class="login-box">
                <h3 style="color: var(--primary-color); margin-bottom: 20px; font-size: 1.3rem;">Обліковий запис</h3>
                <a href="login.php"><button><i class="fas fa-sign-in-alt" style="margin-right: 10px;"></i>
                        Вхід</button></a>
                <a href="registration.php"><button style="background: var(--primary-color); color: white;"><i
                            class="fas fa-user-plus" style="margin-right: 10px;"></i> Реєстрація</button></a>
                <!--  ЗАБЫЛ ПАРОЛЬ НЕ РОБОТАЕТ   -->
                <!-- <a href="reset-password.php"><button><i class="fas fa-key" style="margin-right: 10px;"></i> Забули
                        пароль?</button></a> -->
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
    include("dropdown.php");
    ?>
    <script src="js/main.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const toggleButtons = document.querySelectorAll('.toggle-password');
            toggleButtons.forEach(button => {
                button.addEventListener('click', function () {
                    const input = this.previousElementSibling;
                    const icon = this.querySelector('i');

                    if (input.type === 'password') {
                        input.type = 'text';
                        icon.classList.remove('fa-eye');
                        icon.classList.add('fa-eye-slash');
                    } else {
                        input.type = 'password';
                        icon.classList.remove('fa-eye-slash');
                        icon.classList.add('fa-eye');
                    }
                });
            });

        });
    </script>
</body>

</html>
<?php
$_SESSION['get'] = false;
$_SESSION['errors'] = false;
?>