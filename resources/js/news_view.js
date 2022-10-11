import fancybox from '@fancyapps/fancybox';

var setHeight = function () {
    var size = $('.picture').height();
    $('.gallery').css('height', size + 'px');
};

$(document).ready(function () {
    setHeight();
});

$(window).on('resize', function () {
    setHeight();
});
