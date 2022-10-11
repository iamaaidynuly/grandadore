window.$ = window.jQuery = require("jquery");
require('jquery-easing');
import 'bootstrap';
import Swiper, {Autoplay, EffectFade, Lazy, Navigation, Pagination} from 'swiper';
import 'swiper/swiper-bundle.css';
import Cookies from 'js-cookie';
import Toast from 'toastr';
import 'inputmask';

window.toastr = Toast;

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
require('jquery.rateit');
Swiper.use([Autoplay, Pagination, Navigation, EffectFade, Lazy]);
window.Swiper = Swiper;
window.Cookies = Cookies;

window.sendChangeRateRequest = function (itemId, rating) {
    $.ajax({
        url: window.customConfig.changeRatingUrl,
        data: {
            itemId: itemId,
            rating: rating,
        },
        type: 'PUT'
    });
};

let wrapperElements = document.getElementsByClassName('product-slider-wrapper');

for (let i = 0; i < wrapperElements.length; i++) {
    wrapperElements[i].addEventListener('click', function (event) {
        let element = $(event.target);
        const baseWidth = 17.9219;

        if ($(event.target).hasClass('rateit-hover')) {
            const rating = Math.round(element.width() / baseWidth)
            element.closest('.rateit').rateit('value', rating)
            sendChangeRateRequest(element.closest('.rateit').attr('data-item-id'), rating)
        }
    })
}

$(document).ready(function () {
    initInputMask();
    let width = $(window).width();
    if (width >= 992) {
        $("header").prepend($(".megadropdown"));
        $("header > .container-fluid").prepend($('.basket__mini'));
        $("header > .container-fluid").prepend($('.login__block'));
        $(".level-1").addClass('openMegadropdown');


    } else {
        $(".nav__mobile").prepend($(".megadropdown"));
        $(".basket-modal-content").prepend($('.basket__mini'));
        $(".authorization-modal-content").prepend($('.login__block'));
        $(".level-1").removeClass('openMegadropdown')
    }

    if ($(window).width() <= 991) {
        $('.level-1-open').click(function () {
            $('.level-1-open').hide();
            $(this).siblings('.level-1').show();
        })

        $('.level-1-back').click(function () {
            $('.level-1-open').show();
            $('.level-1').hide();
        })

        $('.level-2-open').click(function () {
            $('.level-1-back').hide();
            $('.level-2-open').hide();
            $(this).siblings('.level-2').show();
        })

        $('.level-2-back').click(function () {
            $('.level-1-back').show();
            $('.level-2-open').show();
            $('.level-2').hide();
        })
    }
})

//var diff;

function initInputMask() {
    let elements = document.getElementsByClassName('masked-phone-inputs');
    for (let i = 0; i < elements.length; i++) {
        let im = new Inputmask("+77 999999999");
        im.mask(elements[i]);
    }
}

$(document).ready(function () {
    initInputMask();
    // someFunction();

    // function someFunction(){
    //     console.log($(this).data('location'));
    //     console.log("ok");
    // }
});

$(window).resize(function () {
    let win = $(this);
    if (win.width() >= 992) {
        $("header").prepend($(".megadropdown"));
        $("header > .container-fluid").prepend($(".basket__mini"));
        $("header > .container-fluid").prepend($('.login__block'));
        $(".level-1").addClass('openMegadropdown');

    } else {
        $(".nav__mobile").prepend($('.megadropdown'));
        $(".basket-modal-content").prepend($('.basket__mini'));
        $(".authorization-modal-content").prepend($('.login__block'));
        $(".level-1").removeClass('openMegadropdown')
    }

    if (win.width() >= 992) {
        if ($('.level-1').css('display') == 'block') {
            $('.level-1').css({
                'display': 'none',
            })
        }
    }

    if (win.width() >= 992) {
        menuClose();
        $(".basket-modal").addClass('display-none-important');
        $(".authorization-modal").addClass('display-none-important');
    }
    // else {
    //     $(".basket-modal").removeClass('display-none-important');
    //     $(".authorization-modal").removeClass('display-none-important');
    // }

    if (win.width() <= 991) {
        $('.level-1-open').click(function () {
            $('.level-1-open').hide();
            $(this).siblings('.level-1').show();
        })

        $('.level-1-back').click(function () {
            $('.level-1-open').show();
            $('.level-1').hide();
        })

        $('.level-2-open').click(function () {
            $('.level-1-back').hide();
            $('.level-2-open').hide();
            $(this).siblings('.level-2').show();
        })

        $('.level-2-back').click(function () {
            $('.level-1-back').show();
            $('.level-2-open').show();
            $('.level-2').hide();
        })
    }
});

$(window).mouseup(function (e) {
    let btn4 = $('.search__icons');
    let content4 = $('.form__header');

    let btn5 = $('.search__icons-mobile');
    let content5 = $('.form__header-mobile');

    let btn = $('.li__catalog2');
    let content = $('.openMegadropdown');

    var basket_btn = $(".mini-basket-openWeb");
    var basket_mini = $(".basket__mini");

    var login_btn = $(".image__userWeb");
    var login_block = $(".login__block");

    if (!basket_btn.is(e.target) && basket_btn.has(e.target).length === 0 && !basket_mini.is(e.target) && basket_mini.has(e.target).length === 0) {
      basket_mini.fadeOut(100);
      $('.swiper-container').removeClass('zplus');
      // alert("ok");
        $(".basket-modal").css({
            "display" : "none",
        })

        $("body").removeClass('popups-scroll-lock');
    }

    if (!login_btn.is(e.target) && login_btn.has(e.target).length === 0 && !login_block.is(e.target) && login_block.has(e.target).length === 0) {
      login_block.fadeOut(100);
        $(".authorization-modal").css({
            "display" : "none",
        })
      $('.swiper-container').removeClass('zplus');
        $("body").removeClass('popups-scroll-lock2');
    }

    if (!btn.is(e.target) && btn.has(e.target).length === 0 && !content.is(e.target) && content.has(e.target).length === 0) {
        content.fadeOut(100);
        $('.swiper-container').removeClass('zplus');
    }

    if (!btn4.is(e.target) && btn4.has(e.target).length === 0 && !content4.is(e.target) && content4.has(e.target).length === 0) {
        content4.removeClass('active');
        $('.search__icons').children('.icon__search').show();
        $('.search__icons').children('.icon__search-close').hide();
        $('.swiper-container').removeClass('zplus');
    }

    if (!btn5.is(e.target) && btn5.has(e.target).length === 0 && !content5.is(e.target) && content5.has(e.target).length === 0) {
        content5.removeClass('active');
        $('.search__icons-mobile').children('.icon__search').show();
        $('.search__icons-mobile').children('.icon__search-close').hide();
        $('.swiper-container').removeClass('zplus');
    }
});

$(document).on('click', 'a[href="#"]', function (event) {
    event.preventDefault();
});

window.searchClose = function () {
    $('.form__header').removeClass('active');
    $('.form__header-mobile').removeClass('active');
    $('.icon__search').show();
    $('.icon__search-close').hide();
}

window.menuClose = function() {
    $('.menu__mobile').hide();
    $('body').removeClass('lock');
    $('.burger__div').removeClass('change');
    $('.burger__div-bottom-wrapper').removeClass('change');
    $('.level-1-open').show();
}

$('.mini-basket-openTablet').click(function () {
    $('#basket-myModalID').show();
    $('.basket__mini').show();
    $("body").addClass('popups-scroll-lock');
})

$('.image__userTablet').click(function () {
    $('#authorization-myModalID').show();
    $('.login__block').show();
    $("body").addClass('popups-scroll-lock2');
})

$('.li__catalog2').on('click', function () {
    $('.level-1').toggle();
    $('.swiper-container').toggleClass('zplus');
});

$('.image__userWeb').on('click', function () {
    $('.login__block').toggle();
    $('.swiper-container').toggleClass('zplus');
});

$('.mini-basket-openWeb').on('click', function () {
    $('.basket__mini').toggle();
    $('.swiper-container').toggleClass('zplus');
});

$('.search__icons').click(function () {
    $('.swiper-container').toggleClass('zplus');
    $(this).children('.icon__search').toggle();
    $(this).children('.icon__search-close').toggle();
    $('.form__header').toggleClass('active');
});

$('.search__icons2').click(function () {
    $('.swiper-container').toggleClass('zplus');
    $(this).children('.icon__search').toggle();
    $(this).children('.icon__search-close').toggle();
    $('.form__header2').toggleClass('active');
});

$('.search__icons-mobile').click(function () {
    $(this).children('.icon__search').toggle();
    $(this).children('.icon__search-close').toggle();
    $('.form__header-mobile').toggleClass('active');
});

$('.burger__div').click(function () {
    window.scrollTo({ top: 0, behavior: 'smooth' });
    $(this).toggleClass('change');
    $('.burger__div-bottom-wrapper').toggleClass('change');
    $('.menu__mobile').toggle();
    $('body').toggleClass('lock');

    if ($('.menu__mobile').css('display') == 'none') {
        $('.level-1-open').show();
    }
});

$('.burger__div-bottom-wrapper').click(function () {
    $('.form__header').removeClass('active');
    $('.icon__search').show();
    $('.icon__search-close').hide();
    $(this).toggleClass('change');
    $('.burger__div').toggleClass('change');
    $('.menu__mobile').toggle();
    $('body').toggleClass('lock');

    if ($('.menu__mobile').css('display') == 'none') {
        $('.form__header-mobile').removeClass('active');
        $('.level-1-open').show();
    }
});

$('.trash').click(function () {
    $(this).parent('.card').remove();
});

// $('.down').click(function () {
//     let $input = $(this).parent().find('input');
//     let count = parseInt($input.val()) - 1;
//     count = count < 1 ? 1 : count;
//     $input.val(count);
//     $input.change();
//     return false;
// });
//
// $('.up').click(function () {
//     let $input = $(this).parent().find('input');
//     $input.val(parseInt($input.val()) + 1);
//     $input.change();
//     return false;
// });

var modal = document.getElementById("myModal");

// Get the button that opens the modal
var btn = document.getElementById("myBtn");

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];

// When the user clicks on the button, open the modal
btn.onclick = function() {
    modal.style.display = "block";
    $("body").addClass("modal-overflow-lock");
}

// When the user clicks on <span> (x), close the modal
span.onclick = function() {
    modal.style.display = "none";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
        $("body").removeClass("modal-overflow-lock");
    }
}

$(".eye").click(function (e) {
    e.preventDefault();
    var type = $(".login__input__password").attr('type');
    switch (type) {
        case 'password': {
            $(".login__input__password").attr('type', 'text');
            return;
        }
        case 'text': {
            $(".login__input__password").attr('type', 'password');
            return;
        };
    };
});

$(".cancel_btn").click(function(){
    $(".colorCheck").css({display: "none"});
    $(".filter-criteria, .filter-item").removeClass("active");
});




