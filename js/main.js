let slider_work = 0;
const slider_wrapper = ['slider/sliderphoto1.jpg', 'slider/slidephoto2.jpg']
function slider_product(cord) {
    if (cord == "right") {
        slider_work++
    }
    else if (cord == "left") {
        slider_work--
    }
    if (slider_work >= slider_wrapper_product.length && cord == "right") {
        slider_work = 0
    }
    else if (slider_work < 0 && cord == "left") {
        slider_work = slider_wrapper_product.length - 1
    }
    set_mimiImg(slider_work);
}
function set_mimiImg(src) {
    slider_work = src;
    const big_img = document.querySelector('.slider_product');
    big_img.src = slider_wrapper_product[slider_work]
}
function openCart() {
    document.getElementById('cartModal').classList.add('show');
    document.getElementById('overlay').classList.add('show');
}
function closeCart() {
    document.getElementById('cartModal').classList.remove('show');
    document.getElementById('overlay').classList.remove('show');
}
document.addEventListener('keydown', function (event) {
    if (event.key === 'Escape') {
        closeCart();
    }
});
function toggleMenu() {

    setTimeout(() => {
        document.getElementById("userMenu").classList.toggle("show");
    }, 100)
}
window.onclick = function (event) {
    if (!event.target.closest('.user-menu-container')) {

        ;
    }
}
const modal = document.getElementById('loginModal');
const btn = document.getElementById('loginBtn');
const span = document.querySelector('.close');

btn.onclick = () => {
    modal.style.display = 'block';
};

span.onclick = () => {
    modal.style.display = 'none';
};

window.onclick = (event) => {
    if (event.target == modal) {
        modal.style.display = 'none';
    }
};
function openLogin() {
    document.getElementById("loginModal").style.display = "block";
}
function toggleCategories(button) {
    const categoriesBlock = button.closest('.categories');
    categoriesBlock.classList.toggle('show');
}

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
    // Инициализация при загрузке страницы
    recalcTotal();
});
// Функции для работы с корзиной
function changeQuantity(button, action, productId) {
    const item = button.closest('.header_card_product');
    if (!item) return;

    const countEl = item.querySelector('.count');
    const priceElement = item.querySelector('.price');
    const pricePerUnit = parseFloat(item.dataset.price) || 0;

    let count = parseInt(countEl.textContent) || 0;

    if (action === 'increase') {
        count++;
    } else if (action === 'decrease' && count > 1) {
        count--;
    } else {
        return;
    }

    countEl.textContent = count;
    const totalPrice = pricePerUnit * count;
    priceElement.textContent = totalPrice.toFixed(2) + ' ₴';

    // Обновляем на сервере
    updateQuantity(productId, count);
    recalcTotal();
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

        // Обновляем отображение цены
        const priceElement = item.querySelector('.price');
        if (priceElement) {
            priceElement.textContent = total.toFixed(2) + ' ₴';
        }
    });

    // Обновляем футер корзины
    updateCartFooter(totalItems, totalSum);
}

function updateCartFooter(totalItems, totalSum) {
    const countFooter = document.getElementById('cart-count');
    const totalFooter = document.getElementById('cart-total');

    if (countFooter && totalFooter) {
        countFooter.textContent = 'В кошику: ' + totalItems + ' ' + getItemWord(totalItems);
        totalFooter.textContent = 'на суму: ' + totalSum.toFixed(2) + ' ₴';
    }
}

function getItemWord(count) {
    if (count == 0) return 'товарів';
    if (count % 10 === 1 && count % 100 !== 11) return 'товар';
    if (count % 10 >= 2 && count % 10 <= 4 && (count % 100 < 10 || count % 100 >= 20)) return 'товари';
    return 'товарів';
}

function updateQuantity(productId, newQuantity) {
    fetch('update.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'product_id=' + productId + '&quantity=' + newQuantity
    }).catch(error => {
        console.log('Ошибка обновления количества:', error);
    });
}

function removeFromCart(productId) {
    if (!confirm('Видалити товар з кошика?')) return;

    const item = document.querySelector('.header_card_product[data-id="' + productId + '"]');
    if (!item) return;

    // Блокируем кнопку
    const deleteBtn = item.querySelector('.delete-btn');
    if (deleteBtn) {
        deleteBtn.style.pointerEvents = 'none';
        deleteBtn.style.opacity = '0.5';
    }

    fetch('removeFromCart.php?product_id=' + productId)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                item.style.opacity = '0';
                item.style.transition = 'opacity 0.3s ease';

                setTimeout(() => {
                    item.remove();
                    recalcTotal();

                    // Если корзина пуста
                    if (document.querySelectorAll('.header_card_product').length === 0) {
                        document.getElementById('cart-items').innerHTML = '<p class="empty-cart">Кошик порожній</p>';
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

// Инициализация при загрузке
document.addEventListener('DOMContentLoaded', function () {
    recalcTotal();
    console.log('Cart initialized');
});

// Функции модального окна
function openCart() {
    document.getElementById('cartModal').classList.add('show');
    document.getElementById('overlay').classList.add('show');
    recalcTotal(); // Пересчитываем при открытии
}

function closeCart() {
    document.getElementById('cartModal').classList.remove('show');
    document.getElementById('overlay').classList.remove('show');
}

// Закрытие по ESC
document.addEventListener('keydown', function (event) {
    if (event.key === 'Escape') {
        closeCart();
    }
});
function addToCart(productId, event) {
    // Получаем кнопку, на которую нажали
    const buyButton = event.target.closest('.buy-btn');
    const originalHtml = buyButton.innerHTML;

    // Показываем анимацию загрузки
    buyButton.innerHTML = '<div class="loading-spinner"></div>';
    buyButton.style.pointerEvents = 'none';
    buyButton.style.opacity = '0.7';

    fetch('addCart.php?product_id=' + productId)
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                showNotification('Товар додано до кошика! ', 'success');

                // НЕМЕДЛЕННО обновляем счетчик из ответа сервера
                updateCartCounter(data.cart_count);

                // Анимация подтверждения
                buyButton.classList.add('added-to-cart');
                setTimeout(() => {
                    buyButton.classList.remove('added-to-cart');
                }, 1000);

            } else {
                showNotification('Помилка при додаванні товару', 'error');
            }
        })
        .catch(error => {
            console.log('Помилка:', error);
            showNotification('Помилка мережі', 'error');
        })
        .finally(() => {
            // Всегда восстанавливаем кнопку
            buyButton.innerHTML = originalHtml;
            buyButton.style.pointerEvents = 'auto';
            buyButton.style.opacity = '1';
        });
}

// Функция для красивого уведомления
// Функция для показа уведомлений
function showNotification(message, type) {
    // Удаляем предыдущие уведомления
    document.querySelectorAll('.notification').forEach(n => n.remove());

    const notification = document.createElement('div');
    notification.className = `notification ${type}`;
    notification.innerHTML = `
        <span>${message}</span>
        <button onclick="this.parentElement.remove()">×</button>
    `;

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

    // Для мобильных устройств
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
    // Получаем актуальное количество товаров
    fetch('getCartCount.php')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateCartCounter(data.count);

                // Если корзина открыта - перезагружаем её содержимое
                if (document.getElementById('cartModal').classList.contains('show')) {
                    loadCartContent();
                }
            }
        })
        .catch(error => {
            console.log('Ошибка обновления корзины:', error);
        });
}
function loadCartContent() {
    fetch('getCartContent.php')
        .then(response => response.text())
        .then(html => {
            document.getElementById('cart-items').innerHTML = html;
            // Пересчитываем общую сумму
            recalcTotal();
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
        } else {
            const newCounter = document.createElement('span');
            newCounter.className = 'cart-counter';
            newCounter.textContent = count;
            cartButton.appendChild(newCounter);

            // Анимация появления
            newCounter.style.opacity = '0';
            newCounter.style.transform = 'scale(0.5)';
            setTimeout(() => {
                newCounter.style.opacity = '1';
                newCounter.style.transform = 'scale(1)';
                newCounter.style.transition = 'all 0.3s ease';
            }, 10);
        }
    } else if (counter) {
        // Анимация исчезновения
        counter.style.opacity = '0';
        counter.style.transform = 'scale(0.5)';
        setTimeout(() => {
            if (counter.parentElement) {
                counter.remove();
            }
        }, 300);
    }

    // Также обновляем корзину если она открыта
    if (document.getElementById('cartModal').classList.contains('show')) {
        setTimeout(() => {
            loadCartContent();
        }, 100);
    }
}
// Инициализация обработчиков событий для корзины
function initCartEventListeners() {
    // Добавляем обработчики для новых элементов корзины
    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.onclick = function () {
            const productId = this.closest('.header_card_product').dataset.id;
            removeFromCart(productId);
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
// Добавьте CSS для анимации
const style = document.createElement('style');
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
// cart.addEventListener('click', function (e) {
//     var plus = e.target.closest('.plus');
//     var minus = e.target.closest('.qty-btn minus');
//     var del = e.target.closest('.delete-btn');

//     if (plus || minus) {
//         // fetch("updata.php", {
//         //     method: "POST",
//         //     headers: {
//         //         "Content-Type": "application/x-www-form-urlencoded"
//         //     },
//         //     body: "name=фывафыва"
//         // })
//         //     .then(response => response.text())
//         //     .then(date => {
//         //         console.log(date);
//         //     })
//         //     .catch(error => console.error(error))

//         var item = plus || minus;
//         item = item.closest('.header_card_product');
//         var countEl = item.querySelector('.count');
//         var count = parseInt(countEl.textContent) || 0;
//         if (plus) count++;
//         if (minus) count = Math.max(0, count - 1);
//         countEl.textContent = count;
//         recalTotal();
//     }

//     if (del) {
//         var item = del.closest('.header_card_product');
//         item.remove();
//         recalTotal();
//     }
// });

// recalTotal();
