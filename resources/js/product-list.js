// require('ion-rangeslider');
import ionRangeSlider from 'ion-rangeslider';
//import jquery_ui from 'jquery-ui-dist/jquery-ui.min.js';

window.ionRangeSlider = ionRangeSlider;
//window.jquery_ui = jquery_ui;
window.needCloseFilters = true;

/*$('.collapse-menu-title').click(function () {
    $(this).toggleClass('active');
    $(this).siblings('.product-types').toggleClass('active');
    // $(this).parent().
})*/


var $range = $(".js-range-slider"),
    $inputFrom = $(".js-input-from"),
    $inputTo = $(".js-input-to"),
    instance,
    min = 0,
    max = 1000,
    from = 0,
    to = 0;

/*$range.ionRangeSlider({
    skin: "big",
    type: "double",
    min: min,
    max: max,
    from: 200,
    to: 800,
    onStart: updateInputs,
    onChange: updateInputs
});*/
// instance = $range.data("ionRangeSlider");

function updateInputs(data) {
    from = data.from;
    to = data.to;

    $inputFrom.prop("value", from);
    $inputTo.prop("value", to);
}

$inputFrom.on("input", function () {
    var val = $(this).prop("value");

    // validate
    if (val < min) {
        val = min;
    } else if (val > to) {
        val = to;
    }

    instance.update({
        from: val
    });
});

$inputTo.on("input", function () {
    var val = $(this).prop("value");

    // validate
    if (val < from) {
        val = from;
    } else if (val > max) {
        val = max;
    }

    instance.update({
        to: val
    });
});

/*window.colors = [];
$.fn.colorSelect = function () {
    var colorItems = "";
    $('.color-select-input').find('option').each(function () {
        colorItems += '' +
            '<li class="'+($(this).prop('selected') ? 'active' : '')+'" style="background:' + this.dataset.color + '" data-colorVal="' + this.dataset.color + '" title="' + this.text + '" data-color-filter="'+this.value+'">' +
            '<span><i class="fa fa-check" aria-hidden="true"></i></span>' +
            '</li>';
        if ($(this).prop('selected')) {
            window.colors.push(this.dataset.color);
        }
    });
    $('.color-select-input').addClass('d-none');
    $('.color-select').html(`<div class="color-select"><ul>${colorItems}</ul></div>`);



}
$(function () {
    $('[data-colorselect]').colorSelect();
})
$('.product-types').on('click', 'ul > li > a', function () {
    $(this).parent().addClass('active');

})
$('.color-select').on('click', 'ul > li', function () {
    $(this).toggleClass('active');
    const color = $(this).attr('data-colorval');

    let action = true;
    if (colors.indexOf(color) === -1) {
        colors.push(color);
    } else {
        action = false;
        window.colors.splice(colors.indexOf(color), 1)
    }

    // $.each(values, function (i, e) {
    $(".color-select-input option[data-color='" + color + "']").prop("selected", action);
    // });
})
$('.size-part').on('click', 'ul > li', function () {
    $(this).toggleClass('selected-size');
})

$('.filter-section-button').click(function () {
    $('.breadcrumb').addClass('d-none');
    $('.product-left-bar').addClass('d-block');
    const windowHeight = $(window).height();
    const header = $('header').height();
    const filterBarHeight = windowHeight - header;
    $('body').addClass('overflow-hidden');
    console.log(header, windowHeight);
    $('#main-container').addClass('product-list-design');
    $('.product-left-bar').css('height', filterBarHeight)
    $('.product-list').parent().addClass('bg-white');

})
window.CloseFilterBtn = function () {
    $('.breadcrumb').removeClass('d-none');
    $('.product-left-bar').removeClass('d-block');
    $('body').removeClass('overflow-hidden');
    $('#main-container').removeClass('product-list-design');
    $('.product-list').parent().removeClass('bg-white');

}

const textBtnDefault = $('.sorting-section-drop > .dropdown-menu >.dropdown-item').first().text();
$('.sorting-section-drop > .sorting-btn').text(textBtnDefault).attr('data-sorting', $(this).attr('data-sorting'));

$('.sorting-section-drop > .dropdown-menu >.dropdown-item').click(function () {
    $('.sorting-section-drop > .dropdown-menu >.dropdown-item').removeClass('active');
    const textBtn = $(this).text();
    $(this).addClass('active');
    $('.sorting-section-drop > .sorting-btn').text(textBtn).attr('data-sorting', $(this).attr('data-sorting'));

    viewModel.setSortingType($(this).attr('data-sorting'));

    viewModel.fetchProducts();
});*/


$(document).ready(function () {
    // var w = $(window).width();
    // if (w <= 575) {
        // $(".modal-content").append($(".filter__block"));
    // }

    // else {
        // $(".filter__web__structure").prepend($(".filter__block"));
    // }

    /*$("#slider-range").slider({
        range: true,
        min: 5000,
        max: 55000,
        values: [5000, 55000],
        slide: function (event, ui) {
            $("#amount_min").val(ui.values[0]);
            $("#amount_max").val(ui.values[1]);
        }
    });
    $("#amount_min").val($("#slider-range").slider("values", 0));
    $("#amount_max").val($("#slider-range").slider("values", 1));
    $("#amount_min").change(function () {
        $("#slider-range").slider("values", 0, $(this).val());
    });
    $("#amount_max").change(function () {
        $("#slider-range").slider("values", 1, $(this).val());
    })*/
})

$(window).resize(function () {
    let w = $(window).width()
    if (w >= 576) {
        $('#myModal').hide();
    }

    // if (w <= 575) {
    //     $(".modal-content").append($(".filter__block"));
    // } else {
    //     $(".filter__web__structure").prepend($(".filter__block"));
    // }
});

$('.product__categories__name').click(function () {
    $(this).siblings().toggle();
});

$('.product__list__name').click(function () {
    $(this).siblings('.product__list__ul').toggleClass('active');
})

$('.changecolor').click(function () {
    $(this).children('.color').children('.colorCheck').toggle();
});

$('.size').click(function () {
    $(this).toggleClass('active');
})

$('.filter-item').click(function () {
    $(this).toggleClass('active');
})

var modal = document.getElementById("myModal");
var btn = document.getElementById("myBtn");
var span = document.getElementsByClassName("close")[0];

btn.onclick = function () {
    modal.style.display = "block";
}

span.onclick = function () {
    modal.style.display = "none";
}

window.onclick = function (event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}

$('.button-apply').click(function () {
    $('#myModal').hide();
});

$('.button-close').click(function () {
    $('.filter-item').removeClass('active');
    $('.size').removeClass('active');
    $('.colorCheck').hide();
    $('#myModal').hide();
});

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

// $('.filter__button').click(function () {
//     $(this).parent('.filter__div').siblings().find('.filter__content').removeClass('active');
//     $(this).parent('.filter__div').siblings().children('.filter__button').children('i').removeClass('rotate');
//     $(this).parent('.filter__div').siblings().children('.filter__button').removeClass('filteractive');
//     $(this).children('i').toggleClass('rotate');
//     $(this).toggleClass('filteractive');
//     $(this).siblings('.filter__content').toggleClass('active');
// })

$(document).mouseup(function (e) {
    var btn = $('.filter__button');
    var content = $('.filter__content');
    var filterssmouseup = $(".filter__block-openedd");

    if (!btn.is(e.target) && btn.has(e.target).length === 0 && !content.is(e.target) && content.has(e.target).length === 0) {
        // content.fadeOut(100);
        // content.fadeOut(100);\
        $('.filter__div').find('.filter__content').removeClass('active');
        $('.filter__div').children('.filter__button').children('i').removeClass('rotate');
        $('.filter__div').children('.filter__button').removeClass('filteractive');
    }

    if (!filterssmouseup.is(e.target) && filterssmouseup.has(e.target).length === 0) {
        $(".filter__block").removeClass("filter__block-openedd");
        $("body").removeClass("scroll-lockk");
    }
});


$(".mobile-filters-open").click(function(){
    $(".filter__block ").addClass('active-filter')
});

$(".filter_block_closer, .accept_btn").click(function(){
    $(".filter__block ").removeClass('active-filter');
});


// $(".mobile-filters-open").click(function(){
//     $(".filter__block ").css({"display": 'block'})
//     $(".filter__block ").animate({"left": '0'});
//
// });
// $(".filter_block_closer").click(function(){
//     $(".filter__block ").animate({"left": '-800'});
// });



// $(".mobile-filters-open").click(function(){
//    $(".filter__block").addClass("filter__block-openedd");
//     $("body").addClass("scroll-lockk");
// });


$('.filter__button').click(function () {
    if ($(window).width() > 575) {
        $(this).parent('.filter__div').siblings().find('.filter__content').removeClass('active');
        $(this).parent('.filter__div').siblings().children('.filter__button').children('i').removeClass('rotate');
        $(this).parent('.filter__div').siblings().children('.filter__button').removeClass('filteractive');
        $(this).children('i').toggleClass('rotate');
        $(this).toggleClass('filteractive');
        $(this).siblings('.filter__content').toggleClass('active');
    }
})


