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
            <form method="GET" action="index.php" class="search-form" id="searchForm">
                <div class="search-container">
                    <i class="fa-solid fa-magnifying-glass search-icon"></i>
                    <input type="text" placeholder="Я шукаю..." name="search" class="search-input" id="searchInput"
                        autocomplete="off" oninput="showSearchSuggestions(this.value)">
                    <button type="submit" class="search-btn">
                        <i class="fa-solid fa-search"></i>
                    </button>
                    <div class="search-suggestions" id="searchSuggestions"></div>
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

<style>
    .search-container {
        position: relative;
    }

    .search-suggestions {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background: linear-gradient(135deg, #ffffff 0%, #f8f9ff 100%);
        border: 2px solid #e0e7ff;
        border-radius: 16px;
        max-height: 300px;
        overflow-y: auto;
        z-index: 1000;
        display: none;
        box-shadow: 0 20px 40px rgba(99, 102, 241, 0.15),
            0 8px 24px rgba(99, 102, 241, 0.1);
        backdrop-filter: blur(10px);
        margin-top: 8px;
        padding: 12px 0;
        transform-origin: top center;
        animation: slideDown 0.3s ease-out;
    }

    .search-suggestion-item {
        padding: 14px 20px;
        cursor: pointer;
        border-bottom: 1px solid #f1f5ff;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        align-items: center;
        gap: 12px;
        position: relative;
        overflow: hidden;
    }

    .search-suggestion-item:last-child {
        border-bottom: none;
    }

    .search-suggestion-item::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        height: 100%;
        width: 3px;
        background: linear-gradient(135deg, #238b4e, #00612b);
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .search-suggestion-item:hover {
        background: linear-gradient(90deg, rgba(99, 102, 241, 0.08) 0%, rgba(139, 92, 246, 0.04) 100%);
        transform: translateX(4px);
    }

    .search-suggestion-item:hover::before {
        opacity: 1;
    }

    .search-suggestion-icon {
        width: 20px;
        height: 20px;
        color: #98f0a7ff;
        opacity: 0.7;
        transition: all 0.3s ease;
    }

    .search-suggestion-item:hover .search-suggestion-icon {
        opacity: 1;
        transform: scale(1.1);
    }

    .search-suggestion-text {
        flex: 1;
        font-size: 14px;
        font-weight: 500;
        color: #374151;
        line-height: 1.4;
    }

    .search-suggestion-highlight {
        color: #89e7a4ff;
        font-weight: 700;
        background: linear-gradient(135deg, #238b4e, #00612b);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        padding: 2px 0;
    }

    .search-suggestion-category {
        font-size: 11px;
        color: #9ca3af;
        font-weight: 500;
        margin-top: 2px;
        display: block;
    }

    .search-suggestion-no-results {
        padding: 20px;
        text-align: center;
        color: #9ca3af;
        font-size: 14px;
    }

    .search-suggestion-no-results i {
        font-size: 24px;
        margin-bottom: 8px;
        display: block;
        color: #d1d5db;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px) scale(0.95);
        }

        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    @keyframes pulse {
        0% {
            transform: scale(1);
        }

        50% {
            transform: scale(1.05);
        }

        100% {
            transform: scale(1);
        }
    }

    .search-suggestions::-webkit-scrollbar {
        width: 6px;
    }

    .search-suggestions::-webkit-scrollbar-track {
        background: rgba(99, 102, 241, 0.1);
        border-radius: 3px;
    }

    .search-suggestions::-webkit-scrollbar-thumb {
        background: linear-gradient(135deg, #238b4e, #00612b);
        border-radius: 3px;
    }

    .search-suggestions::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(135deg, #238b4e, #00612b);
    }

    @media (max-width: 768px) {
        .search-suggestions {
            border-radius: 12px;
            margin-top: 6px;
            padding: 8px 0;
        }

        .search-suggestion-item {
            padding: 12px 16px;
        }

        .search-suggestion-text {
            font-size: 13px;
        }
    }
</style>

<script>
    function showSearchSuggestions(query) {
        const suggestionsContainer = document.getElementById('searchSuggestions');

        if (query.length < 2) {
            suggestionsContainer.style.display = 'none';
            return;
        }
        fetch('data/search_suggestions.php?q=' + encodeURIComponent(query))
            .then(response => response.json())
            .then(suggestions => {
                if (suggestions.length > 0) {
                    suggestionsContainer.innerHTML = suggestions.map(suggestion =>
                        `<div class="search-suggestion-item" onclick="selectSuggestion('${suggestion.replace(/'/g, "\\'")}')">
                         ${highlightMatch(suggestion, query)}
                     </div>`
                    ).join('');
                    suggestionsContainer.style.display = 'block';
                } else {
                    suggestionsContainer.style.display = 'none';
                }
            })
            .catch(error => {
                console.error('Error fetching suggestions:', error);
                suggestionsContainer.style.display = 'none';
            });
    }
    function highlightMatch(text, query) {
        const lowerText = text.toLowerCase();
        const lowerQuery = query.toLowerCase();
        const index = lowerText.indexOf(lowerQuery);

        if (index === -1) return text;

        const before = text.substring(0, index);
        const match = text.substring(index, index + query.length);
        const after = text.substring(index + query.length);

        return `${before}<span class="search-suggestion-highlight">${match}</span>${after}`;
    }
    function selectSuggestion(suggestion) {
        document.getElementById('searchInput').value = suggestion;
        document.getElementById('searchSuggestions').style.display = 'none';
        document.getElementById('searchForm').submit();
    }
    document.addEventListener('click', function (e) {
        const suggestions = document.getElementById('searchSuggestions');
        const searchContainer = document.querySelector('.search-container');

        if (!searchContainer.contains(e.target)) {
            suggestions.style.display = 'none';
        }
    });
    document.getElementById('searchInput').addEventListener('keydown', function (e) {
        const suggestions = document.getElementById('searchSuggestions');
        const items = suggestions.querySelectorAll('.search-suggestion-item');

        if (e.key === 'ArrowDown' && items.length > 0) {
            e.preventDefault();
            items[0].focus();
        }
    });
</script>