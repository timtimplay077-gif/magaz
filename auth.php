
<button id="loginBtn" class="login-btn pointer">
    <i class="fa-regular fa-user"></i>
    <div class="btn-glow"></div>
</button>

<div id="loginModal" class="modal">
    <div class="modal-overlay" onclick="closeLoginModal()"></div>
    <div class="modal-content animated-modal">
        <div class="modal-header">
            <div class="modal-logo">
                <img src="img/kanskrop_logo.png" alt="KansKrop" class="logo-img">
                <div class="logo-glow"></div>
            </div>
            <h2 class="modal-title">Авторизація</h2>
            <span class="close-btn" onclick="closeLoginModal()">
                <i class="fa-solid fa-xmark"></i>
            </span>
        </div>

        <form action="authcheck.php" method="POST" id="authForm" class="auth-form">
            <div class="input-group">
                <label for="login" class="input-label">
                    <i class="fa-solid fa-phone"></i>
                    Телефон
                </label>
                <div class="phone-input-container">
                    <span class="phone-prefix">+380</span>
                    <input type="text" id="login" name="login" placeholder="XX XXX XX XX" required class="phone-input"
                        maxlength="9" pattern="[0-9]{9}" oninput="formatPhoneNumber(this)">
                </div>
            </div>

            <div class="input-group">
                <label for="password" class="input-label">
                    <i class="fa-solid fa-lock"></i>
                    Пароль
                </label>
                <div class="password-container">
                    <input type="password" id="password" name="password" placeholder="Введіть ваш пароль" required
                        class="password-input">
                    </button>
                </div>
            </div>

            <div class="form-footer">
                <div class="register-link">
                    <span>Ще не маєте акаунту? </span>
                    <a href="registration.php" class="register-btn">
                        <i class="fa-solid fa-user-plus"></i>
                        Зареєструватися
                    </a>
                </div>
            </div>

            <div class="submit-container">
                <button class="submit-button" type="submit">
                    <span class="btn-text">Увійти</span>
                    <div class="btn-loader">
                        <div class="loader"></div>
                    </div>
                    <div class="btn-glow-effect"></div>
                </button>
            </div>
        </form>

        <div class="modal-glow"></div>
    </div>
</div>