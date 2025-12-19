document.addEventListener("DOMContentLoaded", function () {
    const swiperRaung = new Swiper(".swiper-raung", {
        loop: true,
        autoplay: {
            delay: 5000,
            disableOnInteraction: false,
        },
        pagination: {
            el: ".raung-pagination",
            clickable: true,
        },
        navigation: {
            nextEl: ".raung-next",
            prevEl: ".raung-prev",
        },
    });

    const swiperJember = new Swiper(".swiper-jember", {
        loop: true,
        autoplay: {
            delay: 5000,
            disableOnInteraction: false,
        },
        pagination: {
            el: ".jember-pagination",
            clickable: true,
        },
        navigation: {
            nextEl: ".jember-next",
            prevEl: ".jember-prev",
        },
    });
});
