const BasketBundleClass = (function () {
    let self = this;

    self.basketItemsCountElementSelector = '.basket-items-count';
    self.basketItemsCountElement = $(self.basketItemsCountElementSelector);

    self.addBasketItemUrl = window.customConfig.addBasketItemUrl;
    self.fetchBasketItemsUrl = window.customConfig.fetchBasketItemsUrl;
    self.fetchSmallBasketUrl = window.customConfig.fetchSmallBasketUrl;
    self.removeBasketItemUrl = window.customConfig.removeBasketItemUrl;
    self.updateBasketItemUrl = window.customConfig.updateBasketItemUrl;

    self.userAuthenticated = window.customConfig.userAuthenticated;
    self.basketItems = [];
    self.basketActionElementSelector = '.basket-action-trigger';
    self.basketActionElements = $(self.basketActionElementSelector);
    self.smallBasketWrapper = $('.small-basket-wrapper');
    self.isProductView = false;
    self.buttonTexts = {
        add: 'Добавить в корзину',
        added: 'Добавлено в корзину'
    };

    self.loaderElement = '<div class="w-100 d-flex justify-content-center align-items-center my-4"><div class="lds-roller"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div></div>';

    self.getItem = function (id) {
        let itemData = null;

        self.basketItems.forEach(function (item) {
            if (item.itemId === id) {
                itemData = item;
            }
        });

        return itemData;
    }

    self.removeItem = function (id) {
        let items = [];

        self.basketItems.forEach(function (item) {
            if (item.itemId !== id) {
                items.push(item);
            }
        });

        self.basketItems = items;
    };

    self.fetchBasket = async function () {
        let response = await fetch(self.fetchBasketItemsUrl);

        self.basketItems = await response.json();
    }

    self.addItem = async function (itemId, count = 1, size = null, colorId = null) {
        let dataToPush = {
            itemId: itemId,
            count: count,
        };

        if (size) {
            dataToPush.sizeId = parseInt(size);
        }
        if (colorId) {
            dataToPush.colorId = parseInt(colorId);
        }

        self.basketItems.push(dataToPush);

        const response = await fetch(self.addBasketItemUrl, {
            method: 'POST',
            mode: 'cors',
            cache: 'no-cache',
            credentials: 'same-origin',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'Content-Type': 'application/json'
            },
            redirect: 'follow',
            referrerPolicy: 'no-referrer',
            body: JSON.stringify(dataToPush)
        });

        return await response.text();
    }

    self.removeItemRequest = async function (itemId, count = 1) {
        self.removeItem(itemId);

        const response = await fetch(self.removeBasketItemUrl, {
            method: 'DELETE',
            mode: 'cors',
            cache: 'no-cache',
            credentials: 'same-origin',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'Content-Type': 'application/json'
            },
            redirect: 'follow',
            referrerPolicy: 'no-referrer',
            body: JSON.stringify({
                itemId: itemId
            })
        });

        return await response.text();
    }

    self.updateItemRequest = async function (itemId, count) {
        const response = await fetch(self.updateBasketItemUrl, {
            method: 'PUT',
            mode: 'cors',
            cache: 'no-cache',
            credentials: 'same-origin',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'Content-Type': 'application/json'
            },
            redirect: 'follow',
            referrerPolicy: 'no-referrer',
            body: JSON.stringify({
                itemId: itemId,
                count: count
            })
        });

        return await response.text();
    };

    self.initTriggersActives = function () {
        self.basketActionElements.removeClass('active');
        self.basketActionElements.filter('.view-action-trigger').attr('data-button-text', self.buttonTexts.add);
        $('.product-view-count').parent().removeClass('disable-inputs');
        let sizeBoxes = $('.size-box');
        sizeBoxes.removeClass('disable active');
        self.basketItems.forEach(itemData => {
            if (itemData.sizeId) {
                $(`[data-size-id="${itemData.sizeId}"].size-box`).addClass('active')
            }

            self.basketActionElements.filter(`[data-item-id="${itemData.itemId}"]`).addClass('active');
            self.basketActionElements.filter(`[data-item-id="${itemData.itemId}"].view-action-trigger`).attr('data-button-text', self.buttonTexts.added);
            $(`[data-item-id="${itemData.itemId}"].product-view-count`).parent().addClass('disable-inputs');
        });

        if ($('.size-box.active').length) {
            sizeBoxes.addClass('disable');
        }
    }

    self.fetchBasket().then(() => {
        self.setBasketCount();
        self.initTriggersActives();
    });

    self.refreshElements = function () {
        self.basketActionElements = $(self.basketActionElementSelector);
        self.initTriggersActives();
    }

    self.setBasketCount = function () {
        let count = 0;

        self.basketItems.forEach(function (item) {
            count += item.count;
        });

        self.basketItemsCountElement.text(count);

        if (self.isProductView) {
            self.basketItems.forEach(function (item) {
                $(`${basketCalculator.itemCountInputSelector}, .product-view-count`).filter(`[data-item-id="${item.itemId}"]`).val(item.count);
            });
        }
    }

    self.animateAdding = function (element) {
        let cart;
        if ($(window).width() > 991) {
            cart = $('.basket-icon');
        }

        if($(window).width() < 992) {
            cart = $('.mini-basket-openTablet');
        }

        $(window).resize(function(){
            if ($(window).width() > 991) {
                cart = $('.basket-icon');
            }

            if($(window).width() < 992) {
                cart = $('.mini-basket-openTablet');
            }
        })

        const cartTop = cart.offset().top;
        let imgToDrag = element.find("img.animatable-image").eq(0);
        if (imgToDrag) {
            let imgToClone = imgToDrag.clone()
                .removeClass('w-100')
                .offset({
                    top: imgToDrag.offset().top,
                    left: imgToDrag.offset().left
                })
                .css({
                    'opacity': '0.8',
                    'position': 'absolute',
                    'height': '150px',
                    'width': '150px',
                    'z-index': '99999'
                })
                .appendTo($('body'))
                .animate({
                    'top': parseInt(cartTop) + 10,
                    'left': cart.offset().left + 10,
                    'width': 75,
                    'height': 75
                }, 700);


            imgToClone.animate({
                'width': 0,
                'height': 0
            }, function () {
                $(this).detach()
            });
        }
    }

    self.constructSmallBasket = function () {
        if (!basketCalculator.isBigBasket) {
            self.smallBasketWrapper.html(self.loaderElement);

            self.fetchSmallBasket().then(response => {
                self.smallBasketWrapper.html(response);
            });
        }
    };

    self.fetchSmallBasket = async function () {
        const response = await fetch(self.fetchSmallBasketUrl, {
            mode: 'cors',
            cache: 'no-cache',
            credentials: 'same-origin',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'Content-Type': 'text/html'
            },
            redirect: 'follow',
            referrerPolicy: 'no-referrer',
        });

        return response.text();
    }

    $(document).on('click', self.basketActionElementSelector, function () {
        let element = $(this);

        const itemId = parseInt(element.attr('data-item-id'));
        let size = null;
        if (itemId && self.getItem(itemId) === null) {

            const sizesCount = parseInt(element.attr('data-sizes-count'));

            if (sizesCount > 0) {
                if (!self.isProductView) {
                    return window.location.href = element.closest('.product-card').find('.item-card-url').attr('href');
                }

                let sizeElements = $('.filter__content .filter-item');
                let activeSize = sizeElements.filter('.active');

                if (!activeSize.length) {
                    return false;
                }

                size = activeSize.attr('data-size-id');
            }

            let count = 1;
            if (self.isProductView) {
                count = parseInt(element.closest('.count-section').find('.product-view-count').val());
            }


            let colorId = $('.color-data-id').data('id')



            self.addItem(itemId, count, size, colorId).then(() => {
                self.initTriggersActives();
                if (!self.isProductView) {
                    self.animateAdding($(this).closest('.product-card'));
                }
                self.constructSmallBasket();
            });

            self.setBasketCount();
        }
        // if (itemId) {
        //     let count = 0;
        //     let countAll = 1;
        //     $('.small-basket-wrapper .basket__body .basket-item-row').each(function () {
        //         if (parseInt($(this).attr('data-item-id')) == itemId) {
        //             count = $(this).find('.basket-item-count').val();
        //         }
        //         countAll += parseInt($(this).find('.basket-item-count').val());
        //     })
        //     count++;
        //     self.updateItemRequest(itemId, count).then(() => {
        //         self.initTriggersActives();
        //         self.constructSmallBasket();
        //         self.basketItemsCountElement.text(countAll);
        //     });
        // }
    }).on('change', '.product-view-count', function () {
        let value = parseInt($(this).val());

        if (value <= 0) {
            $(this).val(1);
            return false;
        }

        const itemId = parseInt($(this).parent().attr('data-item-id'));

        self.updateItemRequest(itemId, value).then(() => {
            self.removeItem(itemId);

            self.basketItems.push({
                itemId: itemId,
                count: value
            });

            self.setBasketCount();
        });
    }).on('change', '.basket-item-count', function () {
        let value = parseInt($(this).val());

        if (value <= 0) {
            $(this).val(1);
            return false;
        }

        const itemId = parseInt($(this).parent().attr('data-item-id'));

        self.updateItemRequest(itemId, value).then(() => {
            self.removeItem(itemId);

            self.basketItems.push({
                itemId: itemId,
                count: value
            });

            self.setBasketCount();
        });
    }).on('click', '.view-count-increment', function () {
        let input = $(this).siblings('.product-view-count');
        let value = parseInt(input.val());

        input.val(++value);
        const itemId = parseInt($(this).parent().attr('data-item-id'));

        if (itemId && self.getItem(itemId)) {
            self.updateItemRequest(itemId, value).then(() => {
                self.removeItem(itemId);

                self.basketItems.push({
                    itemId: itemId,
                    count: value
                });

                self.setBasketCount();
            });
        }
    }).on('click', '.view-count-decrement', function () {
        let input = $(this).siblings('.product-view-count');
        let value = parseInt(input.val());

        if (value <= 1) {
            return false;
        }

        input.val(--value);
        const itemId = parseInt($(this).parent().attr('data-item-id'));

        if (itemId && self.getItem(itemId)) {
            self.updateItemRequest(itemId, value).then(r => {
                self.removeItem(itemId);

                self.basketItems.push({
                    itemId: itemId,
                    count: value
                });

                self.setBasketCount();
            });
        }
    }).on('click', '.disable-inputs', function (event) {
        event.stopPropagation();
        return false;
    }).on('click', '.size-box', function () {
        if (!$(this).hasClass('disable')) {
            $(this).siblings().removeClass('active');
            $(this).addClass('active');

            const price = $(this).attr('data-price');
            const discount = $('.sale-percent').attr('data-discount') || 0;

            $('#discountedPrice').text(basketCalculator.numberFormat(price - (price * discount / 100)));

            if (discount) {
                $('#originalPrice').text(basketCalculator.numberFormat(price));
            }
        }
    });

    return self;
});

window.basketBundle = new BasketBundleClass();

$(document).ready(function () {
    basketBundle.constructSmallBasket();
})
$('.view-action-trigger').on('click',function (){
    $('.product_in_basket').toggleClass('product_in_basket_change')
    $('.product_add').toggleClass('product_add_change')
        setTimeout(function(){
            $('.product_in_basket').removeClass('product_in_basket_change')
            $('.product_add').removeClass('product_add_change')
        }, 2000);
})



