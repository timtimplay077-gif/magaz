<header class="main-header unselectable">
    <div class="header-container">
        <div class="header-logo">
            <a href="index.php" class="logo-link">
                <div class="logo-wrapper">
                    <img src="img/kanskrop_logo.png" alt="KansKrop" class="logo-img">
                    <div class="logo-glow"></div>
                </div>
                <span class="logo-text">КанцКроп</span>
            </a>
        </div>
        <div class="header-search">
            <form method="GET" action="index.php" class="search-form">
                <div class="search-container">
                    <i class="fa-solid fa-magnifying-glass search-icon"></i>
                    <input type="text" placeholder="Я шукаю..." name="search" class="search-input">
                    <button type="submit" class="search-btn">
                        <i class="fa-solid fa-search"></i>
                    </button>
                </div>
            </form>
        </div>
        <div class="header-actions">
            <div class="action-item">
                <?php if ($isLoggedIn): ?>
                    <?php include("dropdown.php") ?>
                    <button class="action-btn user-btn" onclick="toggleMenu()">
                        <i class="fa-regular fa-user"></i>
                        <span class="action-text">Профіль</span>
                    </button>
                <?php else: ?>
                        <?php include("auth.php"); ?>
                        <span class="action-text">Увійти</span>
                <?php endif; ?>
            </div>
            <div class="action-item">
                <?php if ($isLoggedIn): ?>
                    <button class="action-btn cart-btn" onclick="openCartModal()">
                        <div class="cart-icon-wrapper">
                            <i class="fa-solid fa-shopping-cart"></i>
                            <?php if ($cart_count > 0): ?>
                                <span class="cart-counter"><?= $cart_count ?></span>
                            <?php endif; ?>
                        </div>
                        <span class="action-text">Кошик</span>
                    </button>
                <?php else: ?>
                    <button class="action-btn cart-btn" onclick="showNotification('Спочатку авторизуйтесь!', 'error')">
                        <div class="cart-icon-wrapper">
                            <i class="fa-solid fa-shopping-cart"></i>
                            <?php if (isset($_SESSION['cart']) && array_sum($_SESSION['cart']) > 0): ?>
                                <span class="cart-counter"><?= array_sum($_SESSION['cart']) ?></span>
                            <?php endif; ?>
                        </div>
                        <span class="action-text">Кошик</span>
                    </button>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="header-decoration">
        <div class="decoration-line"></div>
        <div class="decoration-dots"></div>
    </div>
</header>