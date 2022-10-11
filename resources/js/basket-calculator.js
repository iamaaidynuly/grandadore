const BasketCalculatorClass = (function () {
    let self = this;

    self.basketItemRowSelector = '.basket-item-row';
    self.priceElementSelector = '.basket-price';
    self.itemCountInputSelector = '.basket-item-count';
    self.itemCountIncrementSelector = '.item-count-increment';
    self.itemCountDecrementSelector = '.item-count-decrement';
    self.itemTotalSelector = '.item-total-price';
    self.basketTotalAmountSelector = '.basket-total-amount';
    self.removeItemSelector = '.remove-item';
    self.updateBasketUrl = window.customConfig.updateBasketItemUrl;
    self.removeBasketItemUrl = window.customConfig.removeBasketItemUrl;
    self.isBigBasket = false;

    self.numberFormat = function (number) {
        return (new Intl.NumberFormat('ru-RU', {
            minimumFractionDigits: 0,
        }).format(number)).replace(',', '.');
    };

    self.calculateOrderForm = function () {
        let orderTotalElement = $('.order-total');
        let deliveryElement = $('#delivery');
        let deliveryPriceElement = $('.delivery-price');

        const deliveryPrice = parseInt(deliveryElement.find('option:selected').attr('data-price'));
        const deliveryMinPrice = parseInt(deliveryElement.find('option:selected').attr('data-min-price'));

        let orderTotal = parseInt(orderTotalElement.attr('data-total'));

        if (orderTotal >= deliveryMinPrice) {
            $('.non-free-delivery').hide();
            $('.free-delivery').show();
        } else {
            $('.non-free-delivery').show();
            $('.free-delivery').hide();
            deliveryPriceElement.text(self.numberFormat(deliveryPrice));
            orderTotal += deliveryPrice;
        }

        orderTotalElement.text(self.numberFormat(orderTotal));
    };

    self.calculateBasket = function () {
        let elements = $(self.basketItemRowSelector);
        let basketTotal = 0;

        $.each(elements, function () {
            let element = $(this);
            const price = parseInt(element.find(self.priceElementSelector).attr('data-price'));
            const count = element.find(self.itemCountInputSelector).val();
            const amount = price * count;


            basketTotal += amount;

            element.find(self.itemTotalSelector).text(self.numberFormat(amount));
        });

        $(self.basketTotalAmountSelector).text(self.numberFormat(basketTotal));
    };

    self.sendChangeCountRequest = async function (itemId, count) {
        count = parseInt(count);
        itemId = parseInt(itemId);
        basketBundle.removeItem(itemId);
        basketBundle.basketItems.push({
           itemId: itemId,
           count: count
        });

        basketBundle.setBasketCount();

        const response = await fetch(self.updateBasketUrl, {
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

    self.sendRemoveFromBasketRequest = async function (itemId) {
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
    };

    $(document).on('change', self.itemCountInputSelector, function () {
        let value = $(this).val();

        if (value <= 0) {
            $(this).val(1);
            value = 1;
        }

        self.sendChangeCountRequest($(this).closest(self.basketItemRowSelector).attr('data-item-id'), value).then(r => {
            self.calculateBasket();
        });

    }).on('click', self.itemCountIncrementSelector, function () {
        let input = $(this).siblings(self.itemCountInputSelector);
        let value = input.val();

        input.val(++value);

        self.sendChangeCountRequest($(this).closest(self.basketItemRowSelector).attr('data-item-id'), value).then(() => {
            self.calculateBasket();
        });
    }).on('click', self.itemCountDecrementSelector, function () {
        let input = $(this).siblings(self.itemCountInputSelector);
        let value = input.val();

        if (value <= 1) {
            return false;
        }

        input.val(--value);

        self.sendChangeCountRequest($(this).closest(self.basketItemRowSelector).attr('data-item-id'), value).then(() => {
            self.calculateBasket();
        });
    }).on('click', self.removeItemSelector, function () {
        const itemId = parseInt($(this).closest(self.basketItemRowSelector).attr('data-item-id'));
        self.sendRemoveFromBasketRequest(itemId).then(() => {
            $(this).closest(self.basketItemRowSelector).remove();
            self.calculateBasket();

            basketBundle.removeItem(itemId);
            basketBundle.setBasketCount();
            basketBundle.refreshElements();

            if (!basketBundle.basketItems.length) {
                basketBundle.constructSmallBasket();
            }
        });
    });

    self.calculateBasket();
});

window.basketCalculator = new BasketCalculatorClass();
