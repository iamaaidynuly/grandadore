import 'lightgallery/src/js/lightgallery.js';
import 'lg-thumbnail/src/lg-thumbnail';
import 'lg-share/src/lg-share';
import 'lg-pager/src/lg-pager';
import 'lg-fullscreen/src/lg-fullscreen';
import 'lg-autoplay/src/lg-autoplay';
import 'lg-hash/src/lg-hash';
import 'lg-zoom/src/lg-zoom';

$(document).ready(function() {
    $("#lightgallery").lightGallery({
        selector: '.item',
        thumbnail:true,
        pager: false

    });
});
