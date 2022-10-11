window.LazyLoadClass = function (params) {
    let self = this;

    self.sourceAttribute = 'data-src';
    self.elements = $('[' + self.sourceAttribute + ']');
    self.placeholderBgColor = '#fbfbfb';
    self.loaderElement = '<div class="lazyload-loader loadingio-spinner-ripple-r9ohe476wme"><div class="ldio-6duixdsy0lv"><div></div><div></div></div></div>';

    self.generatePlaceholder = function (width, height) {
        const placeholder = `<svg xmlns="http://www.w3.org/2000/svg" width="${width}" height="${height}" viewBox="0 0 ${width} ${height}">
                              <rect fill="${self.placeholderBgColor}" width="${width}" height="${height}"/>
                            </svg>`;

        const encoded = encodeURIComponent(placeholder
            .replace(/[\t\n\r]/gim, '')
            .replace(/\s\s+/g, ' ')
            .replace(/'/gim, '\\i')
        )
            .replace(/\(/g, '%28')
            .replace(/\)/g, '%29');

        return `data:image/svg+xml;charset=UTF-8,${encoded}`
    };

    self.refreshElements = async function()
    {
        self.elements = $('[' + self.sourceAttribute + ']');
        self.init();
    }

    self.setPlaceholder = function (element) {
        const placeholderSizes = element.attr('data-lazyload-placeholder');

        if (placeholderSizes) {
            const sizes = placeholderSizes.split('x');
            const width = sizes[0];
            const height = sizes[1];
            self.setSource(element, self.generatePlaceholder(width, height));
        }
    };

    self.setLoader = function (element) {
        element.wrap('<div class="lazyload-loader-wrapper" style="position: relative; width: 100%; height: auto;"></div>');
        element.parent().append(self.loaderElement);
    };

    self.removeLoaders = function () {
        self.elements.unwrap('div.lazyload-loader-wrapper');
        $('.lazyload-loader').remove();
    }

    self.setSource = function (element, source) {
        element.attr('src', source);
    };

    self.loadImages = function () {
        self.elements.each(function () {
            let element = $(this);

            if (!element.hasClass('lazyload-loaded')) {
                self.setSource(element, element.attr(self.sourceAttribute));

                element.addClass('lazyload-loaded');
            }
        });

        if (params.loader) {
            self.removeLoaders();
        }
    };

    self.init = function () {
        self.elements.each(function () {
            let element = $(this);

            self.setPlaceholder(element);

            if (params.loader) {
                self.setLoader(element);
            }
        });
    }
};

window.lazyLoader = new LazyLoadClass({
    loader: true
});

lazyLoader.init();

$(document).ready(function () {
    lazyLoader.loadImages();
});
