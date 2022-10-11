import fancybox from '@fancyapps/fancybox';

$(window).resize(function () {
    $(window).width() > 991 ? $('.about__details').height($('.brand__image').height()) : $('.about__details').height(400)
})

$(window).width() > 991 ? $('.about__details').height($('.brand__image').height()) : $('.about__details').height(400)


$('.fas.fa-star.rating').on('mouseover', function () {
    let onStar = parseInt($(this).attr('data-rating'), 10);

    $(this).parent().children('.fas.fa-star.rating').each(function (e) {
        if (e < onStar) {
            $(this).addClass('hovered');
        }
        else {
            $(this).removeClass('hovered');
        }
    });

}).on('mouseout', function () {
    $(this).parent().children('.fas.fa-star.rating').each(function (e) {
        $(this).removeClass('hovered');
    });
});

$('.fas.fa-star.rating').on('click', function () {
    let onStar = parseInt($(this).attr('data-rating'), 10);
    let stars = $(this).parent().children('.fas.fa-star.rating');

    for (i = 0; i < stars.length; i++) {
        $(stars[i]).removeClass('rated');
    }

    for (i = 0; i < onStar; i++) {
        $(stars[i]).addClass('rated');
    }
});


