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
    var cart = document.getElementById('cartModal');
    var countFooter = document.getElementById('cart-count');
    var totalFooter = document.getElementById('cart-total');

    function getItemWord(count) {
        if (count === 1) {
            return 'товар';
        } else if (count >= 2 && count <= 4) {
            return 'товари';
        } else {
            return 'товарів';
        }
    }

    function recalTotal() {
        var total = 0;
        var itemsCount = 0;
        var items = cart.querySelectorAll('.header_card_product');

        items.forEach(function (item) {
            var price = parseFloat(item.dataset.price) || 0;
            var count = parseInt(item.querySelector('.count').textContent) || 0;
            itemsCount += count;
            total += price * count;
            item.querySelector('.price').textContent = (price * count).toLocaleString('uk-UA');
        });

        countFooter.textContent = 'В кошику: ' + itemsCount + ' ' + getItemWord(itemsCount);
        totalFooter.textContent = '⠀на суму: ' + total.toLocaleString('uk-UA') + '⠀₴';
    }
    document.addEventListener("click", function (e) {
        const plus = e.target.closest(".plus");
        const minus = e.target.closest(".minus");

        if (plus || minus) {
            const item = e.target.closest(".header_card_product, .cart-item");
            const countEl = item.querySelector(".count");
            let count = parseInt(countEl.textContent) || 0;

            if (plus) count++;
            if (minus && count > 1) count--;  // не даём уйти в 0

            countEl.textContent = count;

            recalcTotal();
        }

        del.addEventListener('click', function (e) {
            e.preventDefault();
            var url = this.href;

            fetch(url)
                .then(response => response.text())
                .then(data => {
                    var item = del.closest('.header_card_product');
                    if (item) item.remove();
                    recalcTotal();
                });
        });
    });
});
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
