let currentModalIndex = 0;
let modalImages = [];
let slider_work = 0;
const slider_wrapper = ['slider/sliderphoto1.jpg', 'slider/slidephoto2.jpg'];

function createElement(tag, attributes = {}, textContent = '') {
    const element = document.createElement(tag);

    Object.keys(attributes).forEach(key => {
        if (key === 'class') {
            element.className = attributes[key];
        } else {
            element.setAttribute(key, attributes[key]);
        }
    });

    if (textContent) {
        element.textContent = textContent;
    }

    return element;
}
function getCSRFToken() {
    const metaTag = document.querySelector('meta[name="csrf-token"]');
    return metaTag ? metaTag.getAttribute('content') : '';
}

function slider_product(cord) {
    if (cord === "right") {
        slider_work++;
    } else if (cord === "left") {
        slider_work--;
    }

    if (slider_work >= slider_wrapper_product.length && cord === "right") {
        slider_work = 0;
    } else if (slider_work < 0 && cord === "left") {
        slider_work = slider_wrapper_product.length - 1;
    }

    set_mimiImg(slider_work);
}

function set_mimiImg(src) {
    slider_work = src;
    const big_img = document.querySelector('.slider_product');
    if (big_img) {
        big_img.src = slider_wrapper_product[slider_work];
    }
}

document.addEventListener('keydown', function (event) {
    if (event.key === 'Escape') {
        closeCart();
    }
});

function toggleMenu() {
    setTimeout(() => {
        const userMenu = document.getElementById("userMenu");
        if (userMenu) {
            const isVisible = userMenu.classList.contains("show");
            document.querySelectorAll('.dropdown-menu.show').forEach(otherMenu => {
                if (otherMenu !== userMenu) {
                    otherMenu.classList.remove("show");
                    otherMenu.style.opacity = "0";
                    otherMenu.style.transform = "scale(0.95) translateY(-10px)";
                }
            });
            if (isVisible) {
                userMenu.style.opacity = "0";
                userMenu.style.transform = "scale(0.95) translateY(-10px)";
                setTimeout(() => {
                    userMenu.classList.remove("show");
                }, 150);
            } else {
                userMenu.classList.add("show");
                userMenu.style.opacity = "0";
                userMenu.style.transform = "scale(0.95) translateY(-10px)";
                setTimeout(() => {
                    userMenu.style.opacity = "1";
                    userMenu.style.transform = "scale(1) translateY(0)";
                    userMenu.style.transition = "opacity 0.2s ease, transform 0.2s ease";
                }, 10);
            }
        }
    }, 100);
}
window.addEventListener('click', function (event) {
    if (!event.target.closest('.user-menu-container')) {
        const userMenu = document.getElementById("userMenu");
        if (userMenu && userMenu.classList.contains("show")) {
            userMenu.style.opacity = "0";
            userMenu.style.transform = "scale(0.95) translateY(-10px)";
            setTimeout(() => {
                userMenu.classList.remove("show");
            }, 150);
        }
    }
});

const modal = document.getElementById('loginModal');
const btn = document.getElementById('loginBtn');
const span = document.querySelector('.close');

if (btn) {
    btn.onclick = () => {
        if (modal) {
            modal.style.display = 'block';
        }
    };
}

if (span) {
    span.onclick = () => {
        if (modal) {
            modal.style.display = 'none';
        }
    };
}

window.addEventListener('click', (event) => {
    if (event.target === modal) {
        modal.style.display = 'none';
    }
});

function openLogin() {
    const loginModal = document.getElementById("loginModal");
    if (loginModal) {
        loginModal.style.display = "block";
        setTimeout(() => {
            const modalContent = loginModal.querySelector('.modal-content');
            if (modalContent) {
                modalContent.style.animation = 'modalAppear 0.3s ease';
            }
        }, 10);
    }
}
if (span) {
    span.onclick = () => {
        if (modal) {
            const modalContent = modal.querySelector('.modal-content');
            if (modalContent) {
                modalContent.style.animation = 'modalAppear 0.2s ease reverse';
                setTimeout(() => {
                    modal.style.display = 'none';
                    modalContent.style.animation = '';
                }, 200);
            }
        }
    };
}

function toggleCategories(button) {
    const menu = button.nextElementSibling;
    const isVisible = menu.classList.contains('show');
    document.querySelectorAll('.categories-menu.show').forEach(otherMenu => {
        if (otherMenu !== menu) {
            otherMenu.classList.remove('show');
            otherMenu.previousElementSibling.classList.remove('active');
        }
    });
    menu.classList.toggle('show');
    button.classList.toggle('active');

    if (menu.classList.contains('show')) {
        setTimeout(() => {
            document.addEventListener('click', closeCategoriesMenu);
        }, 0);
    } else {
        document.removeEventListener('click', closeCategoriesMenu);
    }
}

function closeCategoriesMenu(event) {
    const categoriesContainers = document.querySelectorAll('.categories');
    let isClickInside = false;

    categoriesContainers.forEach(container => {
        if (container.contains(event.target)) {
            isClickInside = true;
        }
    });

    if (!isClickInside) {
        document.querySelectorAll('.categories-menu.show').forEach(menu => {
            menu.classList.remove('show');
            menu.previousElementSibling.classList.remove('active');
        });
        document.removeEventListener('click', closeCategoriesMenu);
    }
}

window.addEventListener('resize', () => {
    document.querySelectorAll('.categories-menu.show').forEach(menu => {
        menu.classList.remove('show');
        menu.previousElementSibling.classList.remove('active');
    });
    document.removeEventListener('click', closeCategoriesMenu);
});

window.addEventListener('click', (event) => {
    if (!event.target.closest('.categories')) {
        document.querySelectorAll('.categories.show').forEach(block => {
            block.classList.remove('show');
        });
    }
});

document.addEventListener("DOMContentLoaded", function () {
    const messages = [
        "Купуй товари тільки в KansKrop!",
        "Все для школи та офісу в одному місці",
        "Купуй канцелярію вигідно вже сьогодні!"
    ];

    const marqueeText = document.getElementById("marqueeText");
    if (!marqueeText) return;

    let index = 0;

    function startMarquee(text) {
        marqueeText.style.transform = 'translateX(100%)';
        marqueeText.style.transition = 'none';

        requestAnimationFrame(() => {
            requestAnimationFrame(() => {
                marqueeText.style.transition = 'transform 7s linear';
                marqueeText.style.transform = 'translateX(-100%)';
            });
        });

        setTimeout(() => {
            index = (index + 1) % messages.length;
            startMarquee(messages[index]);
        }, 7500);
    }

    startMarquee(messages[index]);
});

document.addEventListener('DOMContentLoaded', function () {
    recalcTotal();
});

function changeQuantity(button, action, productId, discountPercent = 0) {
    if (!button || !button.closest) return;

    const item = button.closest('.cart-item');
    if (!item) return;

    const countEl = item.querySelector('.quantity-count');
    const totalElement = item.querySelector('.item-total-price');

    const basePrice = parseFloat(item.dataset.price) || 0;
    let count = parseInt(countEl.textContent) || 0;

    if (action === 'increase') {
        count++;
    } else if (action === 'decrease' && count > 1) {
        count--;
    } else {
        return;
    }

    countEl.textContent = count;
    const totalPrice = basePrice * count;
    if (totalElement) {
        totalElement.textContent = totalPrice.toFixed(2) + ' ₴';
    }
    const cartItem = cartData.items.find(i => i.id == productId);
    if (cartItem) {
        cartItem.quantity = count;
        cartItem.total = totalPrice;
        cartData.totalItems = cartData.items.reduce((sum, item) => sum + item.quantity, 0);
        cartData.totalSum = cartData.items.reduce((sum, item) => sum + item.total, 0);
        cartData.totalSumWithoutDiscount = cartData.items.reduce((sum, item) =>
            sum + (item.price_without_discount * item.quantity), 0);
    }
    updateQuantity(productId, count);
    updateCartFooter();
    updateCartCounterGlobally(cartData.totalItems);
    checkAndUpdateMinOrderStatus();
}
function recalcTotal() {
    let totalSum = 0;
    let totalItems = 0;
    const items = document.querySelectorAll('.cart-item');

    items.forEach(function (item) {
        const price = parseFloat(item.dataset.price) || 0;
        const count = parseInt(item.querySelector('.quantity-count').textContent) || 0;
        const total = price * count;

        totalSum += total;
        totalItems += count;
    });
    cartData.totalSum = totalSum;
    cartData.totalItems = totalItems;
    updateCartFooter(totalItems, totalSum);
    updateCartCounterGlobally(totalItems);
    checkAndUpdateMinOrderStatus();
}

function updateCartFooter() {
    const cartFooter = document.getElementById('cart-footer');
    const cartItems = document.querySelectorAll('.cart-item');
    const emptyCartState = document.querySelector('.empty-cart-state');

    if (cartItems.length === 0) {
        cartFooter.style.display = 'none';
        if (emptyCartState) emptyCartState.style.display = 'block';
    } else {
        cartFooter.style.display = 'block';
        if (emptyCartState) emptyCartState.style.display = 'none';
        let totalItems = 0;
        let totalSum = 0;
        let totalSumWithoutDiscount = 0;

        cartItems.forEach(item => {
            const quantity = parseInt(item.querySelector('.quantity-count').textContent);
            const price = parseFloat(item.getAttribute('data-price'));
            const discountPercent = parseFloat(item.getAttribute('data-discount'));

            totalItems += quantity;
            totalSum += price * quantity;
            const priceWithoutDiscount = discountPercent > 0 ?
                price / (1 - discountPercent / 100) : price;
            totalSumWithoutDiscount += priceWithoutDiscount * quantity;
        });
        document.getElementById('total-items-count').textContent =
            totalItems + ' ' + getItemWord(totalItems);

        const discountRow = document.querySelector('.discount-row');
        if (totalSumWithoutDiscount > totalSum) {
            if (!discountRow) {
                const summary = document.querySelector('.cart-summary');
                const discountHtml = `
                    <div class="summary-row discount-row">
                        <span class="summary-label">Знижка:</span>
                        <span class="summary-value" id="total-discount">-${(totalSumWithoutDiscount - totalSum).toFixed(2)} ₴</span>
                    </div>
                `;
                summary.insertBefore(createElementFromHTML(discountHtml), document.querySelector('.total-row'));
            } else {
                discountRow.querySelector('#total-discount').textContent =
                    '-' + (totalSumWithoutDiscount - totalSum).toFixed(2) + ' ₴';
            }
        } else if (discountRow) {
            discountRow.remove();
        }

        document.getElementById('total-sum').textContent =
            totalSum.toFixed(2) + ' ₴';
        updateCartCounter(totalItems);
    }
}
function getItemWord(count) {
    if (count == 0) return 'товарів';
    if (count % 10 === 1 && count % 100 !== 11) return 'товар';
    if (count % 10 >= 2 && count % 10 <= 4 && (count % 100 < 10 || count % 100 >= 20)) return 'товари';
    return 'товарів';
}
function updateQuantity(productId, newQuantity) {
    const params = new URLSearchParams();
    params.append('product_id', productId);
    params.append('quantity', newQuantity);

    fetch('update.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-CSRF-Token': getCSRFToken()
        },
        body: params.toString()
    }).catch(error => {
        console.log('Ошибка обновления количества:', error);
    });
}
function createElementFromHTML(htmlString) {
    const div = document.createElement('div');
    div.innerHTML = htmlString.trim();
    return div.firstChild;
}
function removeFromCart(productId) {
    const item = document.querySelector('.cart-item[data-id="' + productId + '"]');
    if (!item) return;

    const deleteBtn = item.querySelector('.cart-item-remove');
    if (deleteBtn) {
        deleteBtn.style.pointerEvents = 'none';
        deleteBtn.style.opacity = '0.5';
    }

    const params = new URLSearchParams();
    params.append('product_id', productId);

    fetch('removeFromCart.php?' + params.toString(), {
        headers: {
            'X-CSRF-Token': getCSRFToken()
        }
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const itemIndex = cartData.items.findIndex(i => i.id == productId);
                if (itemIndex !== -1) {
                    cartData.items.splice(itemIndex, 1);
                }
                cartData.totalItems = cartData.items.reduce((sum, item) => sum + item.quantity, 0);
                cartData.totalSum = cartData.items.reduce((sum, item) => sum + item.total, 0);
                cartData.totalSumWithoutDiscount = cartData.items.reduce((sum, item) =>
                    sum + (item.price_without_discount * item.quantity), 0);

                item.style.opacity = '0';
                item.style.transition = 'opacity 0.3s ease';

                setTimeout(() => {
                    item.remove();
                    updateCartFooter();
                    updateCartCounterGlobally(cartData.totalItems);
                    checkAndUpdateMinOrderStatus();

                    const cartItems = document.getElementById('cart-items');
                    if (cartItems && document.querySelectorAll('.cart-item').length === 0) {
                        cartItems.innerHTML = ` <div class="empty-cart-state">
                <div class="empty-cart-icon">
                    <i class="fa-solid fa-cart-shopping"></i>
                </div>
                <h3 class="empty-cart-title">Кошик порожній</h3>
                <p class="empty-cart-message">Додайте товари до кошика, щоб зробити покупку</p>
                <button class="empty-cart-button" onclick="closeCartModal()">Продовжити покупки</button>
            </div>`;
                        const cartFooter = document.getElementById('cart-footer');
                        if (cartFooter) cartFooter.style.display = 'none';
                    }
                }, 300);
            } else {
                if (deleteBtn) {
                    deleteBtn.style.pointerEvents = 'auto';
                    deleteBtn.style.opacity = '1';
                }
                alert('Помилка при видаленні товару');
            }
        })
        .catch(error => {
            if (deleteBtn) {
                deleteBtn.style.pointerEvents = 'auto';
                deleteBtn.style.opacity = '1';
            }
            console.log('Ошибка сети:', error);
        });
}
updateCartFooter();
document.addEventListener('DOMContentLoaded', function () {
    recalcTotal();
});

function openCartModal() {
    const modal = document.getElementById('cartModal');
    const overlay = document.createElement('div');
    overlay.id = 'overlay';
    overlay.className = 'overlay';
    overlay.onclick = closeCartModal;
    document.body.appendChild(overlay);

    modal.style.display = 'block';
    setTimeout(() => {
        overlay.classList.add('show');
        modal.classList.add('show');
    }, 10);
}

function closeCartModal() {
    const modal = document.getElementById('cartModal');
    const overlay = document.getElementById('overlay');

    if (overlay) {
        overlay.classList.remove('show');
        modal.classList.remove('show');

        setTimeout(() => {
            if (overlay && overlay.parentElement) {
                overlay.parentElement.removeChild(overlay);
            }
            modal.style.display = 'none';
        }, 300);
    }
}
document.getElementById('overlay').addEventListener('click', closeCartModal);
document.addEventListener('keydown', function (event) {
    if (event.key === 'Escape') {
        closeCart();
    }
});

function addToCart(productId, event) {
    const buyButton = event.target.closest('.buy-btn');
    if (!buyButton) return;

    if (!cartData.isLoggedIn) {
        showNotification('Спочатку авторизуйтесь!', 'error');
        return;
    }

    const originalContent = buyButton.innerHTML;
    buyButton.innerHTML = '<div class="loading-spinner"></div>';
    buyButton.disabled = true;
    buyButton.style.opacity = '0.7';

    const formData = new FormData();
    formData.append('product_id', productId);

    fetch('addCart.php', { method: 'POST', body: formData })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                const discountPercent = cartData.discountPercent || 0;
                let itemPrice = data.item.price;
                let itemTotal = data.item.total;

                if (discountPercent > 0) {
                    const discountMultiplier = (100 - discountPercent) / 100;
                    itemPrice = itemPrice * discountMultiplier;
                    itemTotal = itemPrice * data.item.quantity;
                }

                let existingItem = cartData.items.find(i => i.id == productId);
                if (existingItem) {
                    existingItem.quantity = data.item.quantity;
                    existingItem.price = itemPrice;
                    existingItem.total = itemTotal;
                    existingItem.has_discount = discountPercent > 0;
                } else {
                    const discountedItem = {
                        ...data.item,
                        price: itemPrice,
                        total: itemTotal,
                        has_discount: discountPercent > 0,
                        discount_percent: discountPercent,
                        price_without_discount: discountPercent > 0 ?
                            itemPrice / (1 - discountPercent / 100) : itemPrice
                    };
                    cartData.items.push(discountedItem);
                }

                // ОБНОВЛЯЕМ ОБЩИЕ СУММЫ
                cartData.totalItems = data.cart_count;
                cartData.totalSum = cartData.items.reduce((sum, item) => sum + item.total, 0);
                cartData.totalSumWithoutDiscount = cartData.items.reduce((sum, item) =>
                    sum + (item.price_without_discount * item.quantity), 0);

                showNotification('Товар додано до кошика!', 'success');
                buyButton.innerHTML = '<span>У кошику</span>';
                buyButton.classList.add('in-cart');
                buyButton.disabled = true;
                buyButton.style.opacity = '1';

                updateCartCounterGlobally(data.cart_count);
                updateCartUI();
                setTimeout(() => {
                    checkAndUpdateMinOrderStatus();
                }, 100);

            } else {
                showNotification('Помилка при додаванні товару: ' + data.message, 'error');
                buyButton.innerHTML = originalContent;
                buyButton.disabled = false;
                buyButton.style.opacity = '1';
            }
        })
        .catch(() => {
            buyButton.innerHTML = originalContent;
            buyButton.disabled = false;
            buyButton.style.opacity = '1';
        });
}
function updateCartUI() {
    const cartItems = document.getElementById('cart-items');
    if (!cartItems) return;

    cartItems.innerHTML = '';
    if (cartData.items.length === 0) {
        cartItems.innerHTML = `
            <div class="empty-cart-state">
                <div class="empty-cart-icon">
                    <i class="fa-solid fa-cart-shopping"></i>
                </div>
                <h3 class="empty-cart-title">Кошик порожній</h3>
                <p class="empty-cart-message">Додайте товари до кошика, щоб зробити покупку</p>
                <button class="empty-cart-button" onclick="closeCartModal()">Продовжити покупки</button>
            </div>
        `;
        const cartFooter = document.getElementById('cart-footer');
        if (cartFooter) cartFooter.style.display = 'none';
        return;
    }
    const cartFooter = document.getElementById('cart-footer');
    if (cartFooter) cartFooter.style.display = 'block';

    cartData.items.forEach(item => {
        const div = document.createElement('div');
        div.className = 'cart-item';
        div.dataset.id = item.id;
        div.dataset.price = item.price;
        div.dataset.discount = item.discount_percent || 0;
        div.innerHTML = `
            <div class="cart-item-image">
                <img src="${item.img}" alt="${item.name}">
            </div>
            <div class="cart-item-details">
                <h3 class="cart-item-name">${item.name}</h3>
                <div class="cart-item-price">
                    ${item.has_discount ? `
                        <span class="original-price">${(item.price_without_discount || item.price / (1 - item.discount_percent / 100)).toFixed(2)} ₴</span>
                    ` : ''}
                    <span class="final-price ${item.has_discount ? 'discounted' : ''}">
                        ${item.price.toFixed(2)} ₴
                    </span>
                </div>
            </div>
            <div class="cart-item-controls">
                <div class="quantity-controls">
                    <button class="qty-btn minus" onclick="changeQuantity(this, 'decrease', ${item.id}, ${item.discount_percent || 0})">-</button>
                    <span class="quantity-count">${item.quantity}</span>
                    <button class="qty-btn plus" onclick="changeQuantity(this, 'increase', ${item.id}, ${item.discount_percent || 0})">+</button>
                </div>
                <div class="cart-item-total">
                    <span class="item-total-price">${item.total.toFixed(2)} ₴</span>
                </div>
                <a href="#" class="cart-item-remove" onclick="removeFromCart(${item.id}); return false;">
                    <i class="fa-solid fa-trash"></i>
                </a>
            </div>
        `;
        cartItems.appendChild(div);
    });
    updateCartFooter();
    checkAndUpdateMinOrderStatus();
}
function showNotification(message, type) {
    document.querySelectorAll('.notification').forEach(n => n.remove());
    const notification = createElement('div', { class: `notification ${type}` });
    const messageSpan = createElement('span', {}, message);
    const closeButton = createElement('button', {}, '×');
    closeButton.addEventListener('click', function () {
        notification.remove();
    });

    notification.appendChild(messageSpan);
    notification.appendChild(closeButton);

    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 15px 20px;
        border-radius: 8px;
        color: white;
        font-weight: bold;
        z-index: 10000;
        display: flex;
        align-items: center;
        gap: 10px;
        animation: slideIn 0.3s ease;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        font-size: 14px;
    `;

    notification.style.background = type === 'success' ? '#4CAF50' : '#f44336';

    if (window.innerWidth <= 768) {
        notification.style.top = '10px';
        notification.style.right = '10px';
        notification.style.left = '10px';
        notification.style.fontSize = '13px';
    }

    document.body.appendChild(notification);

    setTimeout(() => {
        if (notification.parentElement) {
            notification.style.animation = 'slideOut 0.3s ease';
            setTimeout(() => {
                if (notification.parentElement) {
                    notification.remove();
                }
            }, 300);
        }
    }, 3000);
}

function updateCartInterface() {
    fetch('getCartCount.php', {
        headers: {
            'X-CSRF-Token': getCSRFToken()
        }
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateCartCounter(data.count);

                const cartModal = document.getElementById('cartModal');
                if (cartModal && cartModal.classList.contains('show')) {
                    loadCartContent();
                }
            }
        })
        .catch(error => {
            console.log('Ошибка обновления корзины:', error);
        });
}

function loadCartContent() {
    fetch('getCartContent.php', {
        headers: {
            'X-CSRF-Token': getCSRFToken()
        }
    })
        .then(response => response.text())
        .then(html => {
            const cartItems = document.getElementById('cart-items');
            if (cartItems) {
                cartItems.innerHTML = html;
                recalcTotal();
            }
        })
        .catch(error => {
            console.log('Ошибка загрузки корзины:', error);
        });
}

function updateCartCounter(count) {
    const cartCounter = document.querySelector('.cart-counter');
    if (count > 0) {
        if (!cartCounter) {
            const cartBtn = document.querySelector('.cart-btn');
            const counter = document.createElement('span');
            counter.className = 'cart-counter';
            counter.textContent = count;
            cartBtn.appendChild(counter);
        } else {
            cartCounter.textContent = count;
        }
    } else if (cartCounter) {
        cartCounter.remove();
    }
}

function initCartEventListeners() {
    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.onclick = function () {
            const item = this.closest('.header_card_product');
            if (item) {
                const productId = item.dataset.id;
                removeFromCart(productId);
            }
        };
    });

    document.querySelectorAll('.qty-btn.plus').forEach(btn => {
        btn.onclick = function () {
            changeQuantity(this, 'increase');
        };
    });

    document.querySelectorAll('.qty-btn.minus').forEach(btn => {
        btn.onclick = function () {
            changeQuantity(this, 'decrease');
        };
    });
}
if (!document.querySelector('#notification-styles')) {
    const style = createElement('style', { id: 'notification-styles' });
    style.textContent = `
        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        
        .notification button {
            background: none;
            border: none;
            color: white;
            font-size: 18px;
            cursor: pointer;
            padding: 0;
            margin: 0;
        }
    `;
    document.head.appendChild(style);
}
const originalFetch = window.fetch;
window.fetch = function (...args) {
    return originalFetch.apply(this, args)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network error');
            }
            return response;
        })
        .catch(error => {
            console.log('Fetch error:', error);
            showNotification('Помилка зʼєднання ', 'error');
            throw error;
        });
};
function updateCartCounterGlobally(count) {
    const counters = document.querySelectorAll('.cart-counter');

    if (count > 0) {
        if (counters.length > 0) {
            counters.forEach(counter => {
                counter.textContent = count;
                counter.classList.add('update');
                setTimeout(() => counter.classList.remove('update'), 300);
            });
        } else {
            const cartButtons = document.querySelectorAll('.cart-btn');
            cartButtons.forEach(btn => {
                const newCounter = document.createElement('span');
                newCounter.className = 'cart-counter';
                newCounter.textContent = count;
                btn.appendChild(newCounter);

                newCounter.style.opacity = '0';
                newCounter.style.transform = 'scale(0.5)';

                setTimeout(() => {
                    newCounter.style.opacity = '1';
                    newCounter.style.transform = 'scale(1)';
                    newCounter.style.transition = 'all 0.3s ease';
                }, 10);
            });
        }
    } else {
        counters.forEach(counter => {
            counter.style.opacity = '0';
            counter.style.transform = 'scale(0.5)';

            setTimeout(() => {
                if (counter.parentElement) {
                    counter.remove();
                }
            }, 300);
        });
    }
    const userMenuCounter = document.querySelector('.user-menu-cart-count');
    if (userMenuCounter) {
        userMenuCounter.textContent = count > 0 ? count : '';
    }
}
function updateGlobalCartCount() {
    let totalItems = 0;
    const items = document.querySelectorAll('.header_card_product');

    items.forEach(function (item) {
        const count = parseInt(item.querySelector('.count').textContent) || 0;
        totalItems += count;
    });
    updateCartCounterGlobally(totalItems);
    syncCartCountWithServer(totalItems);
}
function applyDiscountToCart() {
    const discountPercent = cartData.discountPercent || 0;

    if (discountPercent > 0) {
        const discountMultiplier = (100 - discountPercent) / 100;
        cartData.items.forEach(item => {
            const originalPrice = item.price / discountMultiplier;
            item.original_price = originalPrice;
            item.price = originalPrice * discountMultiplier;
            item.total = item.price * item.quantity;
            item.has_discount = true;
            item.discount_percent = discountPercent;
        });
        cartData.totalSum = cartData.items.reduce((sum, item) => sum + item.total, 0);
        cartData.totalSumWithoutDiscount = cartData.items.reduce((sum, item) =>
            sum + (item.original_price * item.quantity), 0);
        updateCartUI();
    }

}
function syncCartCountWithServer(totalCount) {
    const params = new URLSearchParams();
    params.append('total_count', totalCount);

    fetch('updateCartCount.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-CSRF-Token': getCSRFToken()
        },
        body: params.toString()
    }).catch(error => {
        console.log('Ошибка синхронизации счёта корзины:', error);
    });
}
function updateProductButtons() {
    document.querySelectorAll('.buy-btn').forEach(btn => {
        const productId = btn.getAttribute('onclick').match(/\d+/)[0];
        const isInCart = cartData.items.some(item => item.id == productId);

        if (isInCart) {
            btn.innerHTML = '<span>У кошику</span>';
            btn.classList.add('in-cart');
            btn.disabled = true;
            btn.style.opacity = '1';
        } else {
            btn.innerHTML = '<span>Купити</span>';
            btn.classList.remove('in-cart');
            btn.disabled = false;
            btn.style.opacity = '1';
        }
    });
}
document.addEventListener('DOMContentLoaded', function () {
    recalcTotal();
    console.log('Cart initialized');
    if (cartData.isLoggedIn && cartData.discountPercent > 0) {
        applyDiscountToCart();
    }
});
document.addEventListener('DOMContentLoaded', function () {
    const loginInput = document.getElementById('login');
    const phonePrefix = document.querySelector('.phone-prefix');

    if (loginInput) {
        loginInput.addEventListener('input', function (e) {
            if (/^\d+$/.test(e.target.value)) {
                if (!e.target.value.startsWith('+380')) {
                    let cleanValue = e.target.value.replace(/\D/g, '');
                    if (cleanValue.startsWith('380')) {
                        e.target.value = '+' + cleanValue;
                    }
                    else if (cleanValue.startsWith('80')) {
                        e.target.value = '+3' + cleanValue;
                    }
                    else if (cleanValue.startsWith('0')) {
                        e.target.value = '+38' + cleanValue;
                    }
                    else if (cleanValue.length > 0) {
                        e.target.value = '+380' + cleanValue;
                    }
                }
            }
        });
        loginInput.addEventListener('focus', function () {
            if (!this.value.includes('@')) {
                phonePrefix.style.display = 'block';
            } else {
                phonePrefix.style.display = 'none';
            }
        });

        loginInput.addEventListener('blur', function () {
            phonePrefix.style.display = 'none';
        });
        loginInput.addEventListener('change', function () {
            if (this.value.includes('@')) {
                phonePrefix.style.display = 'none';
            } else {
                phonePrefix.style.display = 'block';
                if (this.value && !this.value.startsWith('+380') && /^\d+$/.test(this.value)) {
                    this.value = '+380' + this.value;
                }
            }
        });
    }
});
document.getElementById('authForm')?.addEventListener('submit', function (e) {
    const loginInput = document.getElementById('login');
    const passwordInput = document.getElementById('password');

    if (loginInput && passwordInput) {
        let loginValue = loginInput.value.trim();
        if (!loginValue.includes('@') && loginValue) {
            if (!loginValue.startsWith('+380')) {
                let cleanValue = loginValue.replace(/\D/g, '');

                if (cleanValue.startsWith('380')) {
                    loginValue = '+' + cleanValue;
                } else if (cleanValue.startsWith('80')) {
                    loginValue = '+3' + cleanValue;
                } else if (cleanValue.startsWith('0')) {
                    loginValue = '+38' + cleanValue;
                } else {
                    loginValue = '+380' + cleanValue;
                }

                loginInput.value = loginValue;
            }
        }
    }
});



function confirmLogout() {
    const overlay = document.createElement('div');
    overlay.id = 'logout-overlay';
    overlay.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.7);
        z-index: 10000;
        display: flex;
        justify-content: center;
        align-items: center;
    `;
    const modal = document.createElement('div');
    modal.id = 'logout-modal';
    modal.style.cssText = `
        background: white;
        padding: 30px;
        border-radius: 15px;
        text-align: center;
        box-shadow: 0 10px 40px rgba(0,0,0,0.3);
        max-width: 400px;
        width: 90%;
        animation: modalSlideIn 0.3s ease;
    `;

    modal.innerHTML = `
        <h3 style="margin-bottom: 20px; color: #333; font-size: 20px;">Ви впевнені, що хочете вийти?</h3>
        <div style="display: flex; gap: 15px; justify-content: center;">
            <button onclick="performLogout()" style="
                background: #e74c3c;
                color: white;
                border: none;
                padding: 12px 25px;
                border-radius: 8px;
                cursor: pointer;
                font-weight: bold;
                font-size: 16px;
                transition: all 0.2s ease;
            ">Так, вийти</button>
            <button onclick="closeLogoutModal()" style="
                background: #95a5a6;
                color: white;
                border: none;
                padding: 12px 25px;
                border-radius: 8px;
                cursor: pointer;
                font-weight: bold;
                font-size: 16px;
                transition: all 0.2s ease;
            ">Скасувати</button>
        </div>
    `;

    overlay.appendChild(modal);
    document.body.appendChild(overlay);
    modal.addEventListener('click', function (e) {
        e.stopPropagation();
    });
}


function performLogout() {
    window.location.href = 'logaut.php';
}
document.addEventListener('click', function (e) {
    if (e.target.id === 'logout-overlay') {
        closeLogoutModal();
    }
});
document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') {
        closeLogoutModal();
    }
});
function openLogoutModal(event) {
    if (event) event.preventDefault();

    const modal = document.getElementById('logoutModal');
    const overlay = document.createElement('div');
    overlay.id = 'logout-overlay';
    overlay.className = 'logout-overlay';

    document.body.appendChild(overlay);
    modal.style.display = 'block';
    document.body.classList.add('modal-open');
    setTimeout(() => {
        const modalContent = modal.querySelector('.logout-modal-content');
        if (modalContent) {
            modalContent.style.animation = 'modalAppear 0.3s ease forwards';
        }
        overlay.style.animation = 'overlayAppear 0.3s ease forwards';
    }, 10);
    overlay.onclick = function (e) {
        if (e.target === overlay) {
            closeLogoutModal();
        }
    };
    const escapeHandler = function (e) {
        if (e.key === 'Escape') {
            closeLogoutModal();
            document.removeEventListener('keydown', escapeHandler);
        }
    };
    document.addEventListener('keydown', escapeHandler);
}

function closeLogoutModal() {
    const modal = document.getElementById('logoutModal');
    const overlay = document.getElementById('logout-overlay');

    if (overlay) {
        overlay.style.animation = 'overlayAppear 0.2s ease reverse forwards';
        setTimeout(() => {
            if (overlay.parentNode) {
                overlay.parentNode.removeChild(overlay);
            }
        }, 200);
    }

    if (modal) {
        const modalContent = modal.querySelector('.logout-modal-content');
        if (modalContent) {
            modalContent.style.animation = 'modalAppear 0.2s ease reverse forwards';
        }
        setTimeout(() => {
            modal.style.display = 'none';
        }, 200);
    }

    document.body.classList.remove('modal-open');
}

function performLogout() {
    window.location.href = 'logaut.php';
}
document.addEventListener('click', function (e) {
    if (e.target.closest('.dropdown-menu') && e.target.textContent === 'Вийти з акаунту') {
        openLogoutModal(e);
    }
});
function togglePasswordVisibility() {
    const passwordInput = document.getElementById('password');
    const toggleBtn = document.querySelector('.toggle-password i');

    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        toggleBtn.classList.remove('fa-eye');
        toggleBtn.classList.add('fa-eye-slash');
    } else {
        passwordInput.type = 'password';
        toggleBtn.classList.remove('fa-eye-slash');
        toggleBtn.classList.add('fa-eye');
    }
}
function closeLoginModal() {
    document.getElementById('loginModal').style.display = 'none';
}
function openLoginModal() {
    document.getElementById('loginModal').style.display = 'block';
}
document.getElementById('loginBtn').addEventListener('click', openLoginModal);
document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') {
        closeLoginModal();
    }
});
document.querySelector('.modal-overlay').addEventListener('click', closeLoginModal);
function checkMinOrderAmount() {
    const minAmount = 200;
    const currentTotal = cartData.totalSum || 0;
    return currentTotal >= minAmount;
}
function showMinOrderAlert() {
    const minAmount = 200;
    const currentTotal = cartData.totalSum || 0;
    const needed = minAmount - currentTotal;
}
function updateCheckoutButton() {
    const checkoutBtn = document.querySelector('.checkout-button');
    const isMinReached = checkMinOrderAmount();

    if (checkoutBtn) {
        if (!isMinReached) {
            checkoutBtn.classList.add('disabled');
            checkoutBtn.onclick = function (e) {
                e.preventDefault();
                showMinOrderAlert();
                return false;
            };
        } else {
            checkoutBtn.classList.remove('disabled');
            checkoutBtn.onclick = null;
        }
    }
}
function updateMinOrderNotice(isReached, currentTotal, minAmount) {
    let notice = document.querySelector('.min-order-notice');

    if (!notice) {
        notice = document.createElement('div');
        notice.className = 'min-order-notice';
        const summary = document.querySelector('.cart-summary');
        if (summary) {
            summary.parentNode.insertBefore(notice, summary);
        }
    }

    if (isReached) {
        notice.innerHTML = `
            <i class="fa-solid fa-check-circle"></i>
            <span>Мінімальна сума замовлення досягнута</span>
        `;
        notice.className = 'min-order-notice notice-success';
    } else {
        const needed = minAmount - currentTotal;
        notice.innerHTML = `
            <i class="fa-solid fa-exclamation-triangle"></i>
            <span>
                Мінімальна сума замовлення: <strong>${minAmount.toFixed(2)} ₴</strong>. 
                До мінімуму не вистачає: <strong>${needed.toFixed(2)} ₴</strong>
            </span>
        `;
        notice.className = 'min-order-notice notice-warning pulse-warning';
        setTimeout(() => {
            notice.classList.remove('pulse-warning');
        }, 1000);
    }
}
function checkAndUpdateMinOrderStatus() {
    const minAmount = 200;
    const currentTotal = cartData.totalSum || 0;
    const isMinReached = currentTotal >= minAmount;
    updateMinOrderNotice(isMinReached, currentTotal, minAmount);
    updateCheckoutButton(isMinReached);
    if (!isMinReached) {
        const needed = minAmount - currentTotal;
        highlightMinOrderWarning();
    }
}
function updateCheckoutButton(isMinReached) {
    const checkoutBtn = document.querySelector('.checkout-button');
    if (!checkoutBtn) return;

    if (!isMinReached) {
        checkoutBtn.classList.add('disabled');
        checkoutBtn.onclick = function (e) {
            e.preventDefault();
            showMinOrderAlert();
            return false;
        };
    } else {
        checkoutBtn.classList.remove('disabled');
        checkoutBtn.onclick = null;
        checkoutBtn.href = 'place_an_order.php';
    }
}
function highlightMinOrderWarning() {
    const notice = document.querySelector('.min-order-notice');
    if (notice && notice.classList.contains('notice-warning')) {
        notice.classList.add('pulse-warning');
        setTimeout(() => notice.classList.remove('pulse-warning'), 1000);
    }
}
function showMinOrderAlert() {
    const minAmount = 200;
    const currentTotal = cartData.totalSum || 0;
    const needed = minAmount - currentTotal;
}
function openCartModal() {
    const modal = document.getElementById('cartModal');
    const overlay = document.createElement('div');
    overlay.id = 'overlay';
    overlay.className = 'overlay';
    overlay.onclick = closeCartModal;
    document.body.appendChild(overlay);

    modal.style.display = 'block';
    setTimeout(() => {
        overlay.classList.add('show');
        modal.classList.add('show');
        checkAndUpdateMinOrderStatus();
    }, 10);
}
function openModal(imageIndex) {
    modalImages = [];

    const mainImage = document.querySelector('.slider_product');
    if (mainImage) modalImages.push(mainImage.src);

    document.querySelectorAll('.product_photo_slider img').forEach(thumb => {
        if (thumb.src) modalImages.push(thumb.src);
    });

    if (modalImages.length === 0) return;

    currentModalIndex = imageIndex;
    document.getElementById('imageModal').style.display = 'block';
    document.getElementById('modalImage').src = modalImages[currentModalIndex];

    const prevBtn = document.querySelector('.modal-prev');
    const nextBtn = document.querySelector('.modal-next');

    if (modalImages.length > 1) {
        prevBtn.classList.remove('hidden');
        nextBtn.classList.remove('hidden');
    } else {
        prevBtn.classList.add('hidden');
        nextBtn.classList.add('hidden');
    }

    document.body.style.overflow = 'hidden';
}

function closeModal() {
    document.getElementById('imageModal').style.display = 'none';
    document.body.style.overflow = 'auto';
}

function navigateModal(direction) {
    currentModalIndex = (currentModalIndex + direction + modalImages.length) % modalImages.length;
    document.getElementById('modalImage').src = modalImages[currentModalIndex];
}
document.addEventListener('click', function (e) {
    if (e.target.id === 'imageModal') closeModal();
});

document.addEventListener('keydown', function (e) {
    if (document.getElementById('imageModal').style.display === 'block') {
        if (e.key === 'Escape') closeModal();
        if (e.key === 'ArrowLeft') navigateModal(-1);
        if (e.key === 'ArrowRight') navigateModal(1);
    }
});