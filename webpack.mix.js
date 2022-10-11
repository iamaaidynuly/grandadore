const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix prov\\ides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */
mix.js('resources/js/app.js', 'public/js')
    .js('resources/js/script.js', 'public/js')
    .js('resources/js/rating-bundle.js', 'public/assets/rating-bundle')
    .js('resources/js/lazyLoad-bundle.js', 'public/js')
    .js('resources/js/favorites-bundle.js', 'public/js')
    .js('resources/js/basket-bundle.js', 'public/js')
    .js('resources/js/viewModel-bundle.js', 'public/js')
    .js('resources/js/product-list.js', 'public/js')
    .js('resources/js/company.js', 'public/js')
    .js('resources/js/info.js', 'public/js')
    .js('resources/js/personal-info.js', 'public/js')
    .js('resources/js/news.js', 'public/js')
    .js('resources/js/product-detail.js', 'public/js')
    .js('resources/js/home.js', 'public/js')
    .js('resources/js/basket-calculator.js', 'public/js')

    .js('resources/js/brand.js', 'public/js')
    .js('resources/js/news_view.js', 'public/js')

    .styles(['node_modules/roboto-fontface/css/roboto/roboto-fontface.css'], 'public/css/roboto-fontface.css')
    .sass('resources/sass/app.scss', 'public/css')
    .sass('resources/sass/homepage.scss', 'public/css')
    .sass('resources/sass/lazy-load.scss', 'public/css')
    .sass('resources/sass/product-list.scss', 'public/css')
    .sass('resources/sass/company.scss', 'public/css')
    .sass('resources/sass/info.scss', 'public/css')
    .sass('resources/sass/personal-info.scss', 'public/css')
    .sass('resources/sass/news.scss', 'public/css')
    .sass('resources/sass/product-detail.scss', 'public/css')
    .sass('resources/sass/login.scss', 'public/css')
    .sass('resources/sass/contact.scss', 'public/css')
    .sass('resources/sass/media.scss', 'public/css')

    .sass('resources/sass/breadcrumb.scss', 'public/css')
    .sass('resources/sass/basket.scss', 'public/css')
    .sass('resources/sass/brands.scss', 'public/css')
    .sass('resources/sass/brand.scss', 'public/css')
    .sass('resources/sass/news_view.scss', 'public/css')
    .sass('resources/sass/registration.scss', 'public/css')
    .sass('resources/sass/rating-bundle.scss', 'public/assets/rating-bundle')

    .disableNotifications();
