document.addEventListener("DOMContentLoaded", function () {
    new Swiper(".heroSwiper", {
        slidesPerView: 1,
        spaceBetween: 0,
        loop: true,
        autoplay: {
            delay: 3000,
            disableOnInteraction: false,
        },
        pagination: {
            el: ".hero-pagination",
            clickable: true,
        },
        navigation: {
            nextEl: ".hero-button-next",
            prevEl: ".hero-button-prev",
        },
    });
});
