import Swiper, {Autoplay, EffectFade, Lazy, Navigation, Pagination, Thumbs} from 'swiper';
import 'swiper/swiper-bundle.css';

Swiper.use([Autoplay, Pagination, Navigation, EffectFade, Lazy, Thumbs]);
window.Swiper = Swiper;

/*let initMenuItems = function () {
    if ($(window).width() < 1200) {
        $('.menu-bar').addClass('side-bar');
    } else {
        $('.menu-bar').removeClass('side-bar');
    }
    if ($('body').width() > 480) {
        resizeMenu();
    } else {
        mobileMenu();
    }
};*/

/*$('.site-header-nav-panel').on('click', '.side-bar', function () {
    $('.category-bar').toggleClass('d-block');
    $('.toggle-wrap').toggleClass('active');
    $('body').toggleClass('overflow-hidden');
});*/

// Homepage slider
window.homeSlider = new Swiper('.home-swiper', {
    // cssMode: true,
    loop: true,
    preloadImages: false,
    lazyLoading: true,
    // mousewheel: true,
    keyboard: true,
    slidesPerView: 1,
    lazy: {
        loadPrevNext: true,
    },
    autoplay: {
        delay: 5000,
        // disableOnInteraction: true
    },
    navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
    },

    speed: 1000,
});
    /*on: {
        init: function () {
            setTimeout(function () {
                const sliderHeight = $('.home-top-swiper ').innerHeight();
                categoryBarHeight(sliderHeight);
            }, 800);
        },
    },*/
// });

/*homeSlider.on('slideChange', function () {`
    $(".swiper-pagination-bullet").removeClass("swiper-pagination-bullet-active");
    $(".swiper-pagination-bullet:eq(" + homeSlider.activeIndex + ")").addClass("swiper-pagination-bullet-active");
});*/

// Products slider
/*const cardSwiper = new Swiper('.card-swiper', {
    slidesPerView: 6,
    spaceBetween: 15,
    freeMode: false,
    pagination: {
        el: '.swiper-pagination',
    },
    breakpoints: {
        // when window width is >= 320px
        320: {
            slidesPerView: 2,
            spaceBetween: 5
        },
        // when window width is >= 480px
        480: {
            slidesPerView: 3,
            spaceBetween: 10
        },
        // when window width is >= 640px
        640: {
            slidesPerView: 4,
            spaceBetween: 10
        },
        1024: {
            slidesPerView: 6,
            spaceBetween: 15
        }
    }

})*/

$(document).ready(function () {
    //initMenuItems();
});

$(window).resize(function () {
    /*initMenuItems();
    const sliderHeight = $('.home-top-swiper ').innerHeight();
    categoryBarHeight(sliderHeight);*/
});


