import 'lightgallery/src/js/lightgallery.js';
import 'lg-thumbnail/src/lg-thumbnail';
import 'lg-share/src/lg-share';
import 'lg-pager/src/lg-pager';
import 'lg-fullscreen/src/lg-fullscreen';
import 'lg-autoplay/src/lg-autoplay';
import 'lg-hash/src/lg-hash';
import 'lg-zoom/src/lg-zoom';

$(document).ready(function() {
    $("#info-lightgallery").lightGallery({
        selector: '.item',
        thumbnail:true,
        pager: false

    });
});
$('.info-bar-btn').click(function (){
    $('.info-button').toggleClass('active');
    $('.info-button >.toggle-wrap-info').toggleClass('active');
    $('.info-left-bar').toggleClass('d-block');
})
$(document).on("click", function (event) {
    if($('.info-button').hasClass('active')) {
        if ($(event.target).closest('.info-bar-btn').length === 0) {
            if ($(event.target).closest('.info-button').length === 0) {

                if ($('.info-left-bar').hasClass('d-block')) {
                    $('.info-left-bar').toggleClass('d-block');
                    $('.info-button').toggleClass('active');
                    $('.info-button > .toggle-wrap-info').toggleClass('active');
                    $('body').toggleClass('overflow-hidden');
                }
            }
        }
    }
})
