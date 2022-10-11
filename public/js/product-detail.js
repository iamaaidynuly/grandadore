/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "/";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 12);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/js/product-detail.js":
/*!****************************************!*\
  !*** ./resources/js/product-detail.js ***!
  \****************************************/
/*! no static exports found */
/***/ (function(module, exports) {

// import Swiper, {Autoplay, EffectFade, Lazy, Navigation, Pagination, Thumbs} from 'swiper';
// import 'swiper/swiper-bundle.css';
//
// Swiper.use([Autoplay, Pagination, Navigation, EffectFade, Lazy, Thumbs]);
// window.Swiper = Swiper;
var galleryThumbs = new Swiper('.gallery-thumbs', {
  direction: 'vertical',
  spaceBetween: 5,
  slidesPerView: '5',
  touchRatio: false,
  speed: 1000,
  loop: !1,
  watchSlidesVisibility: !0,
  watchSlidesProgress: !0,
  mousewheel: true,
  slideToClickedSlide: true
});
var galleryTop = new Swiper('.gallery-top', {
  navigation: {
    nextEl: '.swiper-button-next',
    prevEl: '.swiper-button-prev'
  },
  pagination: {
    el: '.swiper-pagination',
    clickable: true
  },
  mousewheel: true,
  keyboard: true,
  speed: 400,
  thumbs: {
    swiper: galleryThumbs
  },
  breakpoints: {
    575: {
      centeredSlides: true,
      loop: true,
      slidesPerView: 2,
      spaceBetween: 10
    },
    992: {
      slidesPerView: 1,
      spaceBetween: 10
    }
  }
});
$('.swiper-small-wrapper .img-small').on('click', function () {
  var image = $(this).attr('src');
  $('.gallery-top-img').attr('src', image);
});
var sliderHeight = $('.gallery-top').height();
$(window).resize(function () {
  var sliderHeight = $('.gallery-top').height();
  $('.gallery-thumbs').height(sliderHeight);
});
$('.size').click(function () {
  $(this).toggleClass('active');
});
$('.color').click(function () {
  $('.color').children('.colorCheck').hide();
  $('.color').removeClass('color-data-id');
  $(this).children('.colorCheck').toggle();
  $(this).addClass('color-data-id');
});
$('.wrapper > .fas.fa-star.rating').on('mouseover', function () {
  var onStar = parseInt($(this).attr('data-rating'), 10);
  $(this).parent().children('.wrapper > .fas.fa-star.rating').each(function (e) {
    if (e < onStar) {
      $(this).addClass('hovered');
    } else {
      $(this).removeClass('hovered');
    }
  });
}).on('mouseout', function () {
  $(this).parent().children('.wrapper > .fas.fa-star.rating').each(function (e) {
    $(this).removeClass('hovered');
  });
});
$('.fas.fa-star.rating').on('click', function () {
  var onStar = parseInt($(this).attr('data-rating'), 10);
  var stars = $(this).parent().children('.wrapper > .fas.fa-star.rating');

  for (i = 0; i < stars.length; i++) {
    $(stars[i]).removeClass('rated');
  }

  for (i = 0; i < onStar; i++) {
    $(stars[i]).addClass('rated');
  }
});
$('.view__all-reviews').click(function () {
  $('.all__reviews').addClass('active');
  $(this).hide();
});
$('.filter-item').click(function () {
  $(this).siblings().removeClass('active');
  $(this).addClass('active');
});
$('.filter__button').click(function () {
  $(this).parent('.filter__div').siblings().find('.filter__content').removeClass('active');
  $(this).parent('.filter__div').siblings().children('.filter__button').children('i').removeClass('rotate');
  $(this).parent('.filter__div').siblings().children('.filter__button').removeClass('filteractive');
  $(this).children('i').toggleClass('rotate');
  $(this).toggleClass('filteractive');
  $(this).siblings('.filter__content').toggleClass('active');
});
$(document).mouseup(function (e) {
  var btn = $('.filter__button');
  var content = $('.filter__content');

  if (!btn.is(e.target) && btn.has(e.target).length === 0 && !content.is(e.target) && content.has(e.target).length === 0) {
    // content.fadeOut(100);
    // content.fadeOut(100);\
    $('.filter__div').find('.filter__content').removeClass('active');
    $('.filter__div').children('.filter__button').children('i').removeClass('rotate');
    $('.filter__div').children('.filter__button').removeClass('filteractive');
  }
});

(function () {
  $('.gallery-thumbs').height(sliderHeight);
  'use strict';

  var breakpoint = window.matchMedia('(max-width:575px)');
  var catalog;

  var breakpointChecker = function breakpointChecker() {
    if (breakpoint.matches === true) {
      if (catalog !== undefined) catalog.destroy(true, true);
      return;
    } else if (breakpoint.matches === false) {
      return enableSwiper();
    }
  };

  var enableSwiper = function enableSwiper() {
    catalog = new Swiper('.catalog-container', {
      loop: false,
      spaceBetween: 20,
      slidesPerView: '3',
      centeredSlides: false,
      a11y: true,
      keyboardControl: true,
      grabCursor: false,
      pagination: '.swiper-pagination',
      paginationClickable: false,
      navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev'
      },
      breakpoints: {
        576: {
          slidesPerView: 2,
          spaceBetween: 20
        },
        769: {
          slidesPerView: 3,
          spaceBetween: 20
        },
        992: {
          slidesPerView: 4,
          spaceBetween: 20
        }
      }
    });
  };

  breakpoint.addListener(breakpointChecker);
  breakpointChecker();
})();
/*$(function () {
    let galleryImg = $('.gallery-top').innerHeight();
    let gallerysection = galleryImg - 100;
    $('.gallery-thumbs').css('height', galleryImg);
    $('.gallery-thumbs>  .swiper-wrapper').css('height', gallerysection);
    let galleryThumbs = new Swiper('.gallery-thumbs', {
        spaceBetween: 10,
        slidesPerView: 4,
        direction: 'vertical',
        loop: false,
        watchSlidesVisibility: true,
        watchSlidesProgress: true,
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
    });
    let galleryTop = new Swiper('.gallery-top', {

        spaceBetween: 10,
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        thumbs: {
            swiper: galleryThumbs,
        },
    });
    let galleryTop2 = new Swiper('.gallery-top2', {

        spaceBetween: 10,
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        }
    });
    resizeTabs();
})
window.resizeTabs = function () {
    let datahref;
    if ($(window).width() < 1200) {
        $('.tab-content').attr('id', 'accordion');
        $('.product-detail > .nav-tabs').addClass('d-none');
        $('.tab-content').each(function () {
            $(this).find('.tab-pane').addClass('show active');
            $(this).find('.btn-link').removeClass(' d-none');
            $(this).find('.collapse').removeClass(' show');
        })
        $(".tab-pane").first().find('.collapse').addClass("show");

    } else {
        $('.tab-content').removeAttr('id');
        $('.product-detail > .nav-tabs').removeClass('d-none');
        $('.tab-content').each(function () {
            $(this).find('.tab-pane').addClass('show');
            $('.nav-tabs >.nav-item').each(function () {
                if ($(this).children('.nav-link').hasClass('active')) {
                    datahref = ($(this).children('.nav-link').attr('href'));
                    $(this).find('.collapse').addClass(' show');
                }
            })
            $('.tab-pane').removeClass('show').removeClass('active');
            $(this).find('.collapse').addClass(' show');
            $(this).removeClass('active');
            $(datahref).addClass('active show');
            $(this).find('.btn-link').addClass(' d-none');


        })
    }
}
$('.required').keydown(function () {
    $('.placeholder ').addClass('d-none');
})
$(window).resize(function () {
    resizeTabs();
});*/
// var modal = document.getElementById("product-detail-myModal");
// var btn = document.getElementById("product-detail-myBtn");
// var span = document.getElementsByClassName("close")[0];
// btn.onclick = function() {
//     modal.style.display = "block";
// }
// span.onclick = function() {
//     modal.style.display = "none";
// }
//


$("#product-detail-myBtn").click(function () {
  $("#product-detail-myModal").show();
  $("body").addClass("product-detail-modal-overflow");
});
$(document).mouseup(function (e) {
  var productModal = $('.product-detail-modal-content');

  if (!productModal.is(e.target) && productModal.has(e.target).length === 0 && !productModal.is(e.target) && productModal.has(e.target).length === 0) {
    $("#product-detail-myModal").hide();
    $("body").removeClass("product-detail-modal-overflow");
  }
}); //*************************************************************************************************************************************************

var slides = document.querySelectorAll(".gallery-top > .swiper-wrapper > .swiper-slide");

if (slides.length < 2) {
  $(".swiper__details").hide();
}

/***/ }),

/***/ 12:
/*!**********************************************!*\
  !*** multi ./resources/js/product-detail.js ***!
  \**********************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! C:\OpenServer\domains\grandadore\resources\js\product-detail.js */"./resources/js/product-detail.js");


/***/ })

/******/ });