<button id="loginBtn" class="pointer"><i class="fa-regular fa-user"></i></button>
<div id="loginModal" class="modal">
    <div class="modal-content animated-modal">
        <span class="close">&times;</span>
        <h2>Авторизація</h2>
        <form action="authcheck.php" method="POST" id="authForm">
            <div class="input-group">
                <label for="login">* Телефон</label>
                <div class="phone-input-container">
                    <span class="phone-prefix">+380</span>
                    <input type="text" id="login" name="login" placeholder="XXXXXXXXX" required>
                </div>
            </div>
            <div class="password">
                <label for="password">* Пароль</label>
                <input type="password" id="password" name="password" placeholder="Пароль" required>
            </div>
            <div class="register">
                <a href="registration.php">Реєстрація</a>
            </div>

            <div class="submet_but">
                <button class="submet_button" type="submit">Увійти</button>
            </div>
        </form>
    </div>
</div>