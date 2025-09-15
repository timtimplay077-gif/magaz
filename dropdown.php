<div class="user-menu-container">
    <div class="dropdown-menu animated-dropdown" id="userMenu">
        <div class="dropdown-header">
            <div class="user-avatar">
                <i class="fa-solid fa-user-circle"></i>
            </div>
            <div class="user-info">
                <span
                    class="user-name"><?= isset($_SESSION['firstName']) ? htmlspecialchars($_SESSION['firstName']) : 'Користувач' ?></span>
                <span class="user-status">У мережі</span>
            </div>
        </div>
        <div class="dropdown-divider"></div>
        <a href="accountinfo.php" class="dropdown-item">
            <i class="fa-solid fa-user-circle"></i>
            <span>Особистий кабінет</span>
            <div class="item-hover-effect"></div>
        </a>
        <a href="#" class="dropdown-item" onclick="openLogoutModal(event)">
            <i class="fa-solid fa-right-from-bracket"></i>
            <span>Вийти з акаунту</span>
            <div class="item-hover-effect"></div>
        </a>
        <div class="dropdown-footer">
            <div class="menu-glow"></div>
        </div>
    </div>
</div>

<div id="logoutModal" class="logout-modal" style="display: none;">
    <div class="logout-modal-overlay" onclick="closeLogoutModal()"></div>
    <div class="logout-modal-content">
        <div class="modal-header">
            <div class="modal-icon">
                <i class="fa-solid fa-door-open"></i>
                <div class="modal-icon-glow"></div>
            </div>
            <h3>Підтвердження виходу</h3>
        </div>
        <p>Ви впевнені, що хочете вийти з облікового запису?</p>
        <div class="logout-modal-buttons">
            <button onclick="performLogout()" class="logout-btn confirm">
                <i class="fa-solid fa-check"></i>
                Так, вийти
            </button>
            <button onclick="closeLogoutModal()" class="logout-btn cancel">
                <i class="fa-solid fa-xmark"></i>
                Скасувати
            </button>
        </div>
        <div class="modal-glow"></div>
    </div>
</div>