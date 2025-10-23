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
    <link rel="stylesheet" href="css/shop.css">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&family=Open+Sans:wght@300..800&family=Poiret+One&family=Roboto:wght@100..900&display=swap"
        rel="stylesheet">
    <script src="https://kit.fontawesome.com/ee9963f31c.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="css/adaptive.css">
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">
    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
    <title>Авторизація</title>
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

        .h2_login {
            text-align: center;
            margin: 30px 0 20px;
        }

        .h2_login h2 {
            font-size: 2.5rem;
            color: var(--text-color);
            font-weight: 700;
            position: relative;
            padding-bottom: 15px;
            margin-bottom: 30px;
        }

        .h2_login h2:after {
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

        .container {
            display: flex;
            gap: 40px;
            justify-content: center;
            margin: 30px auto 50px;
            max-width: 1200px;
        }

        .form-section {
            flex: 1;
            min-width: 400px;
            background: white;
            border-radius: var(--border-radius);
            padding: 40px;
            box-shadow: var(--box-shadow);
            transition: var(--transition);
            position: relative;
            overflow: hidden;
        }

        .form-section:before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: #4CAF50;
        }

        .form-section:hover {
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
            transform: translateY(-5px);
        }

        .form-section h2 {
            color: var(--primary-color);
            margin-bottom: 25px;
            font-size: 1.8rem;
            position: relative;
            padding-bottom: 15px;
            font-weight: 600;
        }

        .form-section h2:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 40px;
            height: 3px;
            background: var(--primary-color);
        }

        .form-section p {
            color: var(--light-text);
            line-height: 1.6;
            margin-bottom: 25px;
            font-size: 1rem;
        }

        .form-section label {
            font-weight: 600;
            color: var(--text-color);
            margin-bottom: 8px;
            display: block;
            font-size: 1rem;
        }

        .form-section input[type="text"],
        .form-section input[type="password"] {
            width: 100%;
            padding: 14px 16px;
            border: 1px solid var(--border-color);
            border-radius: var(--border-radius);
            font-size: 1rem;
            transition: var(--transition);
            box-sizing: border-box;
            background-color: #f8f9fa;
        }

        .form-section input:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(74, 107, 255, 0.2);
            outline: none;
            background-color: white;
        }

        .phone-input-container {
            position: relative;
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .phone-prefix1 {
            background: #f8f9fa;
            padding: 14px 12px;
            border: 1px solid var(--border-color);
            border-right: none;
            border-radius: var(--border-radius) 0 0 var(--border-radius);
            font-weight: 500;
            color: var(--text-color);
            height: 48px;
            display: flex;
            align-items: center;
        }

        #login {
            border-radius: 0 var(--border-radius) var(--border-radius) 0;
            flex: 1;
            height: 48px;
        }

        .p_login_error {
            color: var(--error-color);
            font-size: 0.9rem;
            margin-top: 5px;
            display: block;
            padding: 10px;
            background-color: rgba(220, 53, 69, 0.1);
            border-radius: var(--border-radius);
            border-left: 4px solid var(--error-color);
        }

        .link {
            display: block;
            margin: 20px 0;
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 600;
            transition: var(--transition);
            text-align: center;
        }

        .link:hover {
            color: var(--primary-hover);
            text-decoration: underline;
        }

        .btn {
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

        .btn:before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: var(--transition);
        }

        .btn:hover {
            background: var(--primary-hover);
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(74, 107, 255, 0.3);
        }

        .btn:hover:before {
            left: 100%;
        }

        .password-field {
            position: relative;
            margin-bottom: 20px;
        }

        .toggle-password1 {
            position: absolute;
            right: 15px;
            top: 53px;
            transform: translateY(-50%);
            cursor: pointer;
            color: var(--light-text);
            background: none;
            border: none;
            font-size: 1.2rem;
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

        .form-section {
            animation: fadeIn 0.6s ease-out;
        }

        .form-section:nth-child(2) {
            animation-delay: 0.2s;
        }

        @media (max-width: 992px) {
            .container {
                flex-direction: column;
                align-items: center;
            }

            .form-section {
                min-width: auto;
                width: 100%;
                max-width: 500px;
            }
        }

        @media (max-width: 576px) {
            .form-section {
                padding: 25px 20px;
            }

            .h2_login h2 {
                font-size: 2rem;
            }

            .form-section h2 {
                font-size: 1.5rem;
            }

            .form-section input[type="text"],
            .form-section input[type="password"] {
                padding: 12px 14px;
            }
        }

        .form-section::after {
            content: '';
            position: absolute;
            bottom: 0;
            right: 0;
            width: 100px;
            height: 100px;
            background: radial-gradient(circle, rgba(74, 107, 255, 0.1) 0%, transparent 70%);
            z-index: -1;
        }

        .benefits-icon {
            font-size: 2rem;
            color: var(--primary-color);
            margin-bottom: 15px;
            display: block;
            text-align: center;
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
                    <a href="mainpage.php" class="category-link">
                        <span class="link-text">Канцелярія</span>
                        <div class="link-underline"></div>
                        <div class="link-hover-effect"></div>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="h2_login">
        <div class="block">
            <h2>Авторизація</h2>
        </div>

    </div>

    <div class="container block unselectable">
        <div class="form-section">
            <h2>Постійний покупець</h2>
            <form action="authcheck.php" method="POST" id="loginForm">
                <label for="login">* Телефон</label>
                <div class="phone-input-container">
                    <span class="phone-prefix1">+380</span>
                    <input type="text" id="login" name="login" placeholder="XXXXXXXXX" required>
                </div>

                <div class="password-field">
                    <label for="password">* Пароль</label>
                    <input type="text" id="password" name="password" placeholder="Введіть ваш пароль" required>
                </div>

                <?php if (isset($_SESSION['login_error'])): ?>
                    <p class='p_login_error'><?= $_SESSION['login_error'] ?></p>
                    <?php unset($_SESSION['login_error']); ?>
                <?php endif; ?>
                <button class="btn" type="submit">
                    <i class="fas fa-sign-in-alt" style="margin-right: 10px;"></i>Увійти
                </button>
            </form>
        </div>

        <div class="form-section unselectable">
            <h2>Новий покупець</h2>
            <i class="fas fa-user-plus benefits-icon"></i>
            <p>
                Створення облікового запису допоможе здійснювати покупки швидше та більш зручно.
                Ви також зможете відслідковувати статус замовлень, використовувати закладки,
                переглядати минулі замовлення, та отримувати знижки для постійних покупців.
            </p>
            <a href="registration.php">
                <button class="btn">
                    <i class="fas fa-arrow-right" style="margin-right: 10px;"></i>Продовжити
                </button>
            </a>
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

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const togglePassword = document.querySelector('.toggle-password1');
            if (togglePassword) {
                togglePassword.addEventListener('click', function () {
                    const passwordInput = document.getElementById('password');
                    const icon = this.querySelector('i');

                    if (passwordInput.type === 'password') {
                        passwordInput.type = 'text';
                        icon.classList.remove('fa-eye');
                        icon.classList.add('fa-eye-slash');
                    } else {
                        passwordInput.type = 'password';
                        icon.classList.remove('fa-eye-slash');
                        icon.classList.add('fa-eye');
                    }
                });
            }
            const loginForm = document.getElementById('loginForm');
            if (loginForm) {
                const phoneInput = document.getElementById('login');
                const passwordInput = document.getElementById('password');
                phoneInput.addEventListener('blur', function () {
                    validatePhoneField(this);
                });
                passwordInput.addEventListener('blur', function () {
                    validatePasswordField(this);
                });

                function validatePhoneField(field) {
                    const value = field.value.trim();
                    const phoneRegex = /^\d{9}$/;

                    if (value !== '' && !phoneRegex.test(value)) {
                        field.classList.add('error');
                        showFieldError(field, 'Номер має містити рівно 9 цифр');
                    } else if (value !== '') {
                        field.classList.remove('error');
                        field.classList.add('success');
                        hideFieldError(field);
                    } else {
                        field.classList.remove('error', 'success');
                        hideFieldError(field);
                    }
                }

                function validatePasswordField(field) {
                    const value = field.value.trim();

                    if (value !== '' && value.length < 6) {
                        field.classList.add('error');
                        showFieldError(field, 'Пароль має містити мінімум 6 символів');
                    } else if (value !== '') {
                        field.classList.remove('error');
                        field.classList.add('success');
                        hideFieldError(field);
                    } else {
                        field.classList.remove('error', 'success');
                        hideFieldError(field);
                    }
                }

                function showFieldError(field, message) {
                    hideFieldError(field);
                    const errorElement = document.createElement('p');
                    errorElement.className = 'p_login_error';
                    errorElement.textContent = message;
                    field.parentNode.insertBefore(errorElement, field.nextSibling);
                }

                function hideFieldError(field) {
                    const nextElement = field.nextElementSibling;
                    if (nextElement && nextElement.className === 'p_login_error') {
                        nextElement.remove();
                    }
                }
                loginForm.addEventListener('submit', function (e) {
                    let formIsValid = true;

                    validatePhoneField(phoneInput);
                    validatePasswordField(passwordInput);

                    if (phoneInput.classList.contains('error') || passwordInput.classList.contains('error')) {
                        formIsValid = false;
                    }

                    if (!formIsValid) {
                        e.preventDefault();
                        const firstError = loginForm.querySelector('.error');
                        if (firstError) {
                            firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        }
                    }
                });
            }
        });
    </script>
    <div id="authCheck" data-logged-in="<?php echo $isLoggedIn ? 'true' : 'false'; ?>" style="display: none;"></div>
    <?php

    if ($isLoggedIn) {
        include("dropdown.php");
    }
    ?>
    <script src="js/main.js"></script>
</body>

</html>