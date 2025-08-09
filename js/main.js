
let puk = 0;
const slider_wrapper = ['slider/sliderphoto1.jpg', 'slider/slidephoto2.jpg']
function slider_product(cord) {
    if (cord == "right") {
        puk++
    }
    else if (cord == "left") {
        puk--
    }
    if (puk >= slider_wrapper_product.length && cord == "right") {
        puk = 0
    }
    else if (puk < 0 && cord == "left") {
        puk = slider_wrapper_product.length - 1
    }
    set_mimiImg(puk);
}
function set_mimiImg(src) {
    puk = src;
    const big_img = document.querySelector('.slider_product');
    big_img.src = slider_wrapper_product[puk]
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
    const slides = document.querySelectorAll(".text-slider div");
    let currentIndex = 0;

    function showSlide(index) {
        slides.forEach((slide, i) => {
            slide.classList.toggle("active", i === index);
        });
    }

    function nextSlide() {
        currentIndex = (currentIndex + 1) % slides.length;
        showSlide(currentIndex);
    }
    showSlide(currentIndex);
    setInterval(nextSlide, 6500); 
});