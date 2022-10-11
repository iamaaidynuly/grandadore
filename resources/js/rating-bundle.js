class RatingBundle {
    constructor(selector, params) {
        params = params || {};
        this.selector = selector;
        this.starsCount = params.starsCount || 5;
        this.starElement = '<span class="star-element fa fa-star"></span>';
        this.changeRatingUrl = params.changeRatingUrl;
        this.readOnly = params.readOnly || false;
        this.resetElements();
    }

    buildStars() {
        let starsWrapper = $('<div class="stars-wrapper"></div>');
        if (this.readOnly) {
            starsWrapper.addClass('stars-read-only');
        }
        let stars = this.starElement.repeat(this.starsCount);
        starsWrapper.html(stars);
        this.$elements.html(starsWrapper);
    }

    resetElements() {
        this.$elements = $(this.selector);

        return this;
    }

    registerEvents() {
        let starElements = this.$elements.find('.star-element');
        let self = this;

        if (!this.readOnly) {
            starElements.click(function (event) {
                let element = $(event.target);
                element.siblings().removeClass('active')
                element.addClass('active');
                const parentElement = element.closest(self.selector);
                const rating = self.starsCount - element.index();

                self.sendChangeRateRequest(parentElement.attr('data-item-id'), rating)
            });
        }
    }

    setDefaultValues() {
        let self = this;

        $.each(this.$elements, function (index, element) {
            element = $(element);

            if (element.attr('data-rate-value')) {
                element.find('.star-element').eq(self.starsCount - element.attr('data-rate-value')).addClass('active')
            }
        })
    }

    sendChangeRateRequest(itemId, rating) {
        if (this.readOnly) {
            return false;
        }

        $.ajax({
            url: this.changeRatingUrl,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                itemId: itemId,
                rating: rating,
            },
            type: 'PUT'
        });
    };

    init() {
        this.buildStars();
        this.registerEvents();
        this.setDefaultValues();
    }
}

window.ratingBundle = new RatingBundle('.rating-elements', {
    changeRatingUrl: window.customConfig.changeRatingUrl,
    readOnly:!window.customConfig.userAuthenticated,
})

ratingBundle.init();
