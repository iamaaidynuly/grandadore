var swiper = new Swiper('.home_swiper-top', {
    slidesPerView: 3,
    spaceBetween: 30,
    breakpoints:{
        1000:{
            slidesPerView: 2,
            spaceBetween:15
        },
        600:{
            slidesPerView: 1,
            spaceBetween:10
        }
    }
});
var swiper_gallery = new Swiper('.home_swiper-bottom', {
    slidesPerView: 3,
    spaceBetween: 30,
    loop:true,
    breakpoints:{
        800:{
            slidesPerView: 2,
            spaceBetween:15
        },
        475:{
            slidesPerView: 1,
            spaceBetween:10
        }
    }
});
let equal_widths = $('.equal_widths');
equal();
function equal(){
    if (equal_widths.length) {
        let equal_elements = equal_widths.children().filter(function () {
            return $(this).data('equal');
        });
        if (equal_elements.length) {
            let equal_element;
            equal_elements.each(function () {
                if (equal_element === undefined) {
                    equal_element = $(this);
                } else if (equal_element.outerWidth() < $(this).outerWidth()) {
                    equal_element = $(this);
                }
            });
            equal_elements.css('width', equal_element.outerWidth() + 20+ 'px');
        }
    }
}
