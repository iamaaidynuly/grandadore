const ViewModelClass = (function () {
    let self = this;

    self.wrapperSelector = '#products-wrapper';
    self.paginationItemSelector = 'a.page-link';
    self.colorFilterSelector = '.color-select-input';
    self.colorCirclesSelector = '.color-select';
    self.$rangeSliderElement = $(".js-range-slider");
    self.$rangeInputFrom = $(".js-input-from");
    self.$rangeInputTo = $(".js-input-to");
    self.sortingTypeElement = $('.sorting-section-drop > .dropdown-menu >.dropdown-item').first();
    self.criteriaElements = $('.filter-criteria');
    self.rangeSliderInstance = undefined;
    self.priceRanges = {};
    self.selectedCriteria = [];
    self.wrapperElement = $(self.wrapperSelector);
    self.paginationItemElements = $(self.paginationItemSelector);
    self.colorFilterElement = $(self.colorFilterSelector);
    self.fetchUrl = window.customConfig.fetchProductsUrl;
    self.priceRangeUrl = window.customConfig.priceRangeUrl;
    self.lazyLoader = window.lazyLoader;
    self.categoryAlias = '';
    self.stateChanged = false;

    self.loaderElement = '<div class="w-100 d-flex justify-content-center align-items-center my-4">' +
        '<div class="lds-roller">' +
            '<div></div>' +
            '<div></div>' +
            '<div></div>' +
            '<div></div>' +
            '<div></div>' +
            '<div></div>' +
            '<div></div>' +
            '<div></div>' +
        '</div>' +
    '</div>';

    self.queryParams = {
        page: 1,
        sortType: self.sortingTypeElement.attr('data-sorting'),
        criteria: []
    };

    self.parseQueryString = function () {
        const queryString = window.location.search;
        let query = {};

        if (queryString) {
            let pairs = (queryString[0] === '?' ? queryString.substr(1) : queryString).split('&');
            for (let i = 0; i < pairs.length; i++) {
                let pair = pairs[i].split('=');
                let text = decodeURIComponent(pair[1] || '')
                if (/^[\],:{}\s]*$/.test(text.replace(/\\["\\\/bfnrtu]/g, '@').replace(/"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g, ']').replace(/(?:^|:|,)(?:\s*\[)+/g, ''))) {
                    query[decodeURIComponent(pair[0])] = JSON.parse(decodeURIComponent(pair[1] || ''));
                } else {
                    query[decodeURIComponent(pair[0])] = decodeURIComponent(pair[1] || '');
                }


            }
        }

        query.category = self.categoryAlias;
        self.queryParams = Object.assign(self.queryParams, query);
    };

    self.setCategoryAlias = function () {
        const urlComponents = window.location.pathname.split('/').reverse();

        self.categoryAlias = urlComponents[0];
    };

    self.setSortingType = function (type) {
        self.queryParams.sortType = type;
    };

    self.buildQueryString = function () {
        let queryString = '';

        let loopStart = true;
        $.each(self.queryParams, function (key, value) {
            if (!loopStart) {
                queryString += '&';
            }

            if (typeof value == "object") {
                value = JSON.stringify(value);
            }

            queryString += `${key}=${value}`;
            loopStart = false
        });

        return queryString;
    };

    self.sendRequest = async function () {
        let response = await fetch(self.fetchUrl + '?' + self.buildQueryString());

        return await response.text();
    };


    self.setDataFromQuery = function () {
        const params = self.queryParams;

        $(`[data-category]`).removeClass('active');
        $(`[data-category="${self.categoryAlias}"]`).addClass('active');

        if (params.sortType) {
            $('#dropdownMenu2').empty().text($(`[data-sorting="${params.sortType}"]`).text());
        }

        window.colors = [];
        $('[data-color-filter]').removeClass('active');
        $('.color-select-input option').prop('selected', false);
        if (params.colorFilters && params.colorFilters.length) {
            params.colorFilters.forEach(function (item) {
                let colorOption = $(`.color-select-input option[value="${item}"]`);

                $(`[data-color-filter="${item}"]`).addClass('active');
                colorOption.prop("selected", true);
                window.colors.push(colorOption.attr('data-color'))
            })
        }

        if (self.rangeSliderInstance && params.priceRange) {
            if (params.priceRange.from) {
                self.rangeSliderInstance.update({
                    from: params.priceRange.from
                });
            }
            if (params.priceRange.to) {
                self.rangeSliderInstance.update({
                    to: params.priceRange.to
                });
            }
        }
        self.criteriaElements.removeClass('selected-size');
        if (params.criteria && params.criteria.length) {
            params.criteria.forEach(function (item) {
                self.criteriaElements.filter(`[data-criterion="${item}"]`).addClass('selected-size');
            })
        }
    };

    self.fetchProducts = function (toTop, setState = true) {
        // if (setState) {
        //     self.setState();
        // }
        self.setLoader();
        if (toTop) {
            self.scrollToTop();
        }
        self.sendRequest().then(response => {
            self.wrapperElement.html(response);

            self.lazyLoader.refreshElements().then(() => {
                self.lazyLoader.loadImages();
            });
            window.rateItElements = $('.rateit');
            basketBundle.refreshElements();
            favoritesBundle.refreshElements();
            ratingBundle.resetElements().init();
        });
    }

    self.scrollToTop = function () {
        $('body, html').animate({
            scrollTop: self.wrapperElement.parent().offset().top
        }, 1000);
    };

    self.setLoader = function () {
        self.wrapperElement.html(self.loaderElement);
    };

    self.getPriceRange = async function () {
        let response = await fetch(self.priceRangeUrl + '?' + self.buildQueryString());

        return await response.json();
    };

    self.initRangeSlider = function (min, max) {
        self.$rangeSliderElement.ionRangeSlider({
            skin: "big",
            type: "double",
            min: min,
            max: max,
            from: self.queryParams.priceRange && self.queryParams.priceRange.from ? self.queryParams.priceRange.from : min,
            to: self.queryParams.priceRange && self.queryParams.priceRange.to ? self.queryParams.priceRange.to : max,
            step: 5,
            onStart: self.updateRangeInputs,
            onChange: self.updateRangeInputs,
            onFinish: function (data) {
                self.setPriceRanges(data.from, data.to);

                self.fetchProducts();
            },
        });

        self.rangeSliderInstance = self.$rangeSliderElement.data("ionRangeSlider");
    }

    self.setPriceRanges = function (from, to) {
        if (typeof self.queryParams.priceRange !== "object") {
            self.queryParams.priceRange = {};
        }

        if (from !== undefined) {
            self.queryParams.priceRange.from = from;
        }
        if (to !== undefined) {
            self.queryParams.priceRange.to = to;
        }
    };

    //////////////////////////////////////////////


    $(".cancel_btn").click(function(){

        let min = $('.irs-min').text();
        let max = $('.irs-max').text();
        let val = self.$rangeInputFrom.prop("value");

        if (val < self.priceRanges.min) {
            val = self.priceRanges.min;
        } else if (val > self.priceRanges.max) {
            val = self.priceRanges.min
        }

        self.$rangeInputFrom.prop('value', min);
        self.$rangeInputTo.prop('value', max);
        self.setPriceRanges(parseInt(min));

        self.rangeSliderInstance.update({
            from: min,
            to: max
        });

        self.fetchProducts();

        self.updateRangeInputs({min,max})
    });





      //////////////////////////////////////////////////////

    self.updateRangeInputs = function (data) {
        self.$rangeInputFrom.prop("value", data.from);
        self.$rangeInputTo.prop("value", data.to);
    }

    self.setState = function () {
        if (!self.stateChanged) {
            history.replaceState(self.queryParams, null, '?' + self.buildQueryString(null, null, false));
            self.stateChanged = true;
        } else {
            history.pushState(self.queryParams, null, '?' + self.buildQueryString(null, null, false));
        }
    };

    self.setCategoryAlias();
    self.parseQueryString();
    self.fetchProducts();
    self.setDataFromQuery();
    self.getPriceRange().then(response => {
        self.priceRanges = response;
        const disableRanges = response.min === response.max

        self.initRangeSlider(response.min, response.max);
        self.setPriceRanges(response.min, response.max);
        self.$rangeInputFrom.prop('disabled', disableRanges);
        self.$rangeInputTo.prop('disabled', disableRanges);
    });

    self.wrapperElement.on('click', self.paginationItemSelector, function (event) {
        event.preventDefault();

        if ($(this).attr('rel') === 'prev') {
            self.queryParams.page--;
        } else if ($(this).attr('rel') === 'next') {
            self.queryParams.page++;
        } else {
            self.queryParams.page = $(event.target).text();
        }

        self.fetchProducts(true);
    });



    $(document).on('click', self.colorCirclesSelector, function () {
        const colorFilters = $(self.colorFilterElement).val();

        if (colorFilters.length) {
            self.queryParams.colorFilters = colorFilters;
        } else {
            delete self.queryParams.colorFilters;
        }

        self.fetchProducts();
    });

    self.$rangeInputFrom.on("change", function () {
        let val = $(this).prop("value");

        if (val < self.priceRanges.min) {
            val = self.priceRanges.min;
        } else if (val > self.priceRanges.max) {
            val = self.priceRanges.min
        }

        $(this).prop('value', val);
        self.$rangeInputTo.prop('value', self.priceRanges.max);
        self.setPriceRanges(parseInt(val));

        self.rangeSliderInstance.update({
            from: val,
            to: self.priceRanges.max
        });

        self.fetchProducts();
    });

    self.$rangeInputTo.on("change", function () {
        let val = $(this).prop("value");

        if (val > self.priceRanges.max) {
            val = self.priceRanges.max;
        } else if (val < self.priceRanges.min) {
            val = self.priceRanges.max
        }

        $(this).prop('value', val);

        self.rangeSliderInstance.update({
            to: val
        });

        self.setPriceRanges(undefined, parseInt(val));

        self.fetchProducts();
    });

    self.criteriaElements.click(function () {
        let element = $(this);

        const id = element.attr('data-criterion');
        const idIndex = self.queryParams.criteria.indexOf(id)

        if (idIndex === -1) {
            self.queryParams.criteria.push(id);
        } else {
            self.queryParams.criteria.splice(idIndex, 1)
        }

        self.fetchProducts();
    });

    window.onpopstate = function (event) {
        self.queryParams = event.state;
        self.setDataFromQuery();
        self.stateChanged = false;
        self.fetchProducts();
    }
});

window.viewModel = new ViewModelClass();
