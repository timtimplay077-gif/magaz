
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
            userMenu.classList.toggle("show");
        }
    }, 100);
}

window.addEventListener('click', function (event) {
    if (!event.target.closest('.user-menu-container')) {
        const userMenu = document.getElementById("userMenu");
        if (userMenu && userMenu.classList.contains("show")) {
            userMenu.classList.remove("show");
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
    }
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

    const item = button.closest('.header_card_product');
    if (!item) return;

    const countEl = item.querySelector('.count');
    const priceElement = item.querySelector('.price');
    const oldPriceElement = item.querySelector('.old-price');
    const basePrice = parseFloat(item.dataset.basePrice) || parseFloat(item.dataset.price) / ((100 - discountPercent) / 100);
    let pricePerUnit = parseFloat(item.dataset.price) || 0;

    let count = parseInt(countEl.textContent) || 0;

    if (action === 'increase') {
        count++;
    } else if (action === 'decrease' && count > 1) {
        count--;
    } else {
        return;
    }

    countEl.textContent = count;
    if (discountPercent > 0) {
        const discountMultiplier = (100 - discountPercent) / 100;
        pricePerUnit = basePrice * discountMultiplier;
    }

    const totalPrice = pricePerUnit * count;
    if (priceElement) {
        priceElement.textContent = totalPrice.toFixed(2) + ' ₴';
    }
    if (oldPriceElement && discountPercent > 0) {
        oldPriceElement.textContent = (basePrice * count).toFixed(2) + ' ₴';
    }
    const cartItem = cartData.items.find(i => i.id == productId);
    if (cartItem) {
        cartItem.quantity = count;
        cartItem.total = totalPrice;
        if (discountPercent > 0 && !cartItem.original_price) {
            cartItem.original_price = basePrice;
        }
    }

    updateQuantity(productId, count);
    recalcTotal();
    updateGlobalCartCount();
}
function recalcTotal() {
    let totalSum = 0;
    let totalItems = 0;
    const items = document.querySelectorAll('.header_card_product');

    items.forEach(function (item) {
        const price = parseFloat(item.dataset.price) || 0;
        const count = parseInt(item.querySelector('.count').textContent) || 0;
        const total = price * count;

        totalSum += total;
        totalItems += count;
        const priceElement = item.querySelector('.price');
        if (priceElement) {
            priceElement.textContent = total.toFixed(2) + ' ₴';
        }
    });

    updateCartFooter(totalItems, totalSum);
    updateCartCounterGlobally(totalItems);
}

function updateCartFooter(totalItems, totalSum) {
    const countFooter = document.getElementById('cart-count');
    const totalFooter = document.getElementById('cart-total');

    if (countFooter) {
        countFooter.textContent = 'В кошику: ' + totalItems + ' ' + getItemWord(totalItems);
    }

    if (totalFooter) {
        totalFooter.textContent = '⠀на суму: ' + totalSum.toFixed(2) + ' ₴';
    }
}
function getItemWord(count) {
    if (count === 0) return 'товарів';
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

function removeFromCart(productId) {
    const item = document.querySelector('.header_card_product[data-id="' + productId + '"]');
    if (!item) return;

    const deleteBtn = item.querySelector('.delete-btn');
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

                item.style.opacity = '0';
                item.style.transition = 'opacity 0.3s ease';

                setTimeout(() => {
                    item.remove();
                    recalcTotal();
                    const cartItems = document.getElementById('cart-items');
                    if (cartItems && document.querySelectorAll('.header_card_product').length === 0) {
                        cartItems.innerHTML = '<p class="empty-cart">Кошик порожній</p>';
                    }
                    updateCartCounterGlobally(cartData.items.reduce((total, item) => total + item.quantity, 0));
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
document.addEventListener('DOMContentLoaded', function () {
    recalcTotal();
    console.log('Cart initialized');
});

function openCartModal() {
    const modal = document.getElementById('cartModal');
    const overlay = document.getElementById('overlay');
    modal.classList.add('show');
    overlay.classList.add('show');
}

function closeCartModal() {
    const modal = document.getElementById('cartModal');
    const overlay = document.getElementById('overlay');
    modal.classList.remove('show');
    overlay.classList.remove('show');
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
        alert('Спочатку авторизуйтесь!');
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
                        discount_percent: discountPercent
                    };
                    cartData.items.push(discountedItem);
                }

                buyButton.innerHTML = '<span>У кошику</span>';
                buyButton.classList.add('in-cart');
                buyButton.disabled = true;
                buyButton.style.opacity = '1';

                updateCartCounterGlobally(data.cart_count);
                updateCartUI();
            } else {
                buyButton.innerHTML = originalContent;
                buyButton.disabled = false;
                buyButton.style.opacity = '1';
                alert('Помилка при додаванні товару: ' + data.message);
            }
        })
        .catch(() => {
            buyButton.innerHTML = originalContent;
            buyButton.disabled = false;
            buyButton.style.opacity = '1';
            alert('Помилка мережі');
        });
}



function updateCartUI() {
    const cartItems = document.getElementById('cart-items');
    if (!cartItems) return;

    cartItems.innerHTML = '';
    if (cartData.items.length === 0) {
        cartItems.innerHTML = '<p class="empty-cart">Кошик порожній</p>';
        return;
    }

    cartData.items.forEach(item => {
        const div = document.createElement('div');
        div.className = 'header_card_product';
        div.dataset.id = item.id;
        div.dataset.price = item.price;
        div.dataset.discount = item.discount_percent || 0;
        const priceHTML = item.has_discount && item.original_price ?
            `<div class="price-wrapper">
                <span class="price discounted">${item.total.toFixed(2)} ₴</span>
            </div>` :
            `<div class="price-wrapper">
                <span class="price">${item.total.toFixed(2)} ₴</span>
            </div>`;

        div.innerHTML = `
            <div class="delete-wrapper">
                <a href="#" class="delete-btn" onclick="removeFromCart(${item.id}); return false;">
                    <img src="img/recycle-bin.png" alt="Видалити">
                </a>
            </div>
            <div class="photo-wrapper"><img src="${item.img}" alt="${item.name}"></div>
            <div class="name-wrapper"><p>${item.name}</p></div>
            ${priceHTML}
            <div class="quantity-wrapper">
                <button class="qty-btn minus" onclick="changeQuantity(this, 'decrease', ${item.id}, ${item.discount_percent || 0})">-</button>
                <span class="count">${item.quantity}</span>
                <button class="qty-btn plus" onclick="changeQuantity(this, 'increase', ${item.id}, ${item.discount_percent || 0})">+</button>
            </div>
        `;
        cartItems.appendChild(div);
    });

    const totalItems = cartData.items.reduce((a, i) => a + i.quantity, 0);
    const totalSum = cartData.items.reduce((a, i) => a + i.total, 0);
    const totalSumWithoutDiscount = cartData.items.reduce((a, i) =>
        a + ((i.original_price || i.price) * i.quantity), 0);

    document.getElementById('cart-count').textContent = `В кошику: ${totalItems} ${getItemWord(totalItems)}`;
    const totalFooter = document.getElementById('cart-total');
    if (totalSumWithoutDiscount > totalSum) {
        totalFooter.innerHTML = `
            <span>${totalSum.toFixed(2)} ₴</span>
        `;
    } else {
        totalFooter.textContent = `⠀на суму: ${totalSum.toFixed(2)} ₴`;
    }
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
    const counter = document.querySelector('.cart-counter');
    const cartButton = document.querySelector('.cart-button');

    if (count > 0) {
        if (counter) {
            counter.textContent = count;
            counter.classList.add('update');
            setTimeout(() => counter.classList.remove('update'), 300);
        } else if (cartButton) {
            const newCounter = createElement('span', { class: 'cart-counter' }, count);
            cartButton.appendChild(newCounter);

            newCounter.style.opacity = '0';
            newCounter.style.transform = 'scale(0.5)';

            setTimeout(() => {
                newCounter.style.opacity = '1';
                newCounter.style.transform = 'scale(1)';
                newCounter.style.transition = 'all 0.3s ease';
            }, 10);
        }
    } else if (counter) {
        counter.style.opacity = '0';
        counter.style.transform = 'scale(0.5)';

        setTimeout(() => {
            if (counter.parentElement) {
                counter.remove();
            }
        }, 300);
    }

    const cartModal = document.getElementById('cartModal');
    if (cartModal && cartModal.classList.contains('show')) {
        setTimeout(() => {
            loadCartContent();
        }, 100);
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
