<button id="loginBtn" class="pointer"><i class="fa-regular fa-user"></i></button>
<div id="loginModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Авторизація</h2>
        <form action="authcheck.php">
            <div class="email">
                <label for="email"></label>
                <input type="email" name="email" placeholder="E-mail" required>
            </div>
            <div class="password">
                <label for="password"></label>
                <input type="text" name="password" placeholder="Пароль" required>
            </div>
            <div class="register">
                <a href="registration.php">Реєстрація</a>
            </div>

            <div class="submet_but">
                <button class="submet_button" type="submet">Увійти</button>
            </div>
        </form>
    </div>
</div>