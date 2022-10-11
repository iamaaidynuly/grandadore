<?php

use App\Services\LanguageManager\Facades\LanguageManager;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


if (env('APP_ENV') === 'local') {
    Route::get('get-token', function () {
        return csrf_token();
    });
    Route::get('login/force/{id}', function ($id) {
        Auth::loginUsingId($id);
    });
}

Route::group(['prefix' => config('admin.prefix'), 'middleware' => 'notAdmin'], function () {
    Route::get('/', 'Admin\AuthController@redirectIfAuthenticated');
    Route::get('login', 'Admin\AuthController@login')->name('admin.login');
    Route::post('login', 'Admin\AuthController@attemptLogin');
    Route::get('password/reset', 'Admin\AuthController@reset')->name('admin.password.reset');
    Route::post('password/reset', 'Admin\AuthController@attemptReset');
    Route::get('password/recover/{email}/{token}', 'Admin\AuthController@recover')->where(['email' => '[^\/]+', 'token' => '[^\/]+'])->name('admin.password.recover');
    Route::post('password/recover/{email}/{token}', 'Admin\AuthController@attemptRecover')->where(['email' => '[^\/]+', 'token' => '[^\/]+']);
});

Route::prefix('webhooks')
    ->name('webhooks.')
    ->namespace('Webhooks')
    ->group(function (Router $router) {
        $router->get('paybox/result', 'PayboxController@result')->name('paybox.result');
        $router->get('paybox/success', 'PayboxController@success')->name('paybox.success');
        $router->get('paybox/checking', 'PayboxController@checking')->name('paybox.checking');

        $router->post('kassa24/result', 'Kassa24Controller@result')->name('kassa24.result');
        $router->get('kassa24/success', 'Kassa24Controller@success')->name('kassa24.success');
    });

Route::get('verify-email/{email}/{token}', 'Site\Auth\RegisterController@verifyEmail')->name('verify_email');
Route::post('logout', 'Site\Auth\LoginController@logout')->middleware('logged_in')->name('logout');

Route::post('/yandex', 'Site\MapAjaxController@map')->name('yandex');

Route::middleware('setLocale')->group(function () {
    Route::post('login', 'Site\Auth\LoginController@login')->name('login.post');
    Route::post('order', 'Site\ProductsController@order')->name('order');
    Route::post('register', 'Site\Auth\RegisterController@register')->name('register.post');
    Route::post('reset', 'Site\Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
    Route::post('password/reset/{email}/{token}', 'Site\Auth\ResetPasswordController@reset')->name('password.update');
    Route::post('send-mail', 'Site\AppController@sendMail')->name('contacts.send_mail');
});

Route::middleware(['logged_in'])->group(function () {
    Route::put('items/changeRating', 'Site\ProductsController@changeRating')->name('products.changeRating');
});


/** Bistri zakaz */


Route::post('/bistri-zakaz', [\App\Http\Controllers\Site\AppController::class, 'fastOrder'])->name('fastOrder');


Route::get('/bistri-zakaz', [\App\Http\Controllers\Site\AppController::class, 'fastOrderView'])->name('fastOrderView');


Route::get('basket', 'Site\Cabinet\BasketController@basket')->name('cabinet.basket');
Route::get('basket/list', 'Site\Cabinet\BasketController@getBasketItems')->name('cabinet.basket.get');
Route::post('basket/add', 'Site\Cabinet\BasketController@add')->name('cabinet.basket.add');
Route::put('basket/update', 'Site\Cabinet\BasketController@update')->name('cabinet.basket.update');
Route::delete('basket/remove', 'Site\Cabinet\BasketController@delete')->name('cabinet.basket.delete');

Route::group(['prefix' => LanguageManager::getPrefix(), 'middleware' => 'languageManager'], function () {
    Route::get('smallBasket/list', 'Site\Cabinet\BasketController@getSmallBasket')->name('cabinet.basket.getSmallBasket');
    Route::get('login', 'Site\Auth\LoginController@showLoginForm')->name('login');
    Route::get('register', 'Site\Auth\RegisterController@showRegistrationForm')->name('register');
    Route::get('registerFor', 'Site\Auth\RegisterController@showRegistrationForm1')->name('registerFor');
    Route::get('reset', 'Site\Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
    Route::get('password/reset/{email}/{token}', 'Site\Auth\ResetPasswordController@showResetForm')->name('password.reset');

    /** Brand */
    Route::get(r('brand') . '/{url}', 'Site\BrandsController@brand_view')->name('brand.view');
    /** Brand */
    /** News */
    Route::get(r('news') . '/{url}', 'Site\NewsController@news_item')->name('news');

    Route::get('/faq', 'Site\ProductsController@faq')->name('faq');
    Route::get('/ready', 'Site\ProductsController@ready')->name('ready');
    /** News */

    Route::get(r('product') . '/{url}', 'Site\AppController@product_view')->name('product.view');
    Route::get('products/getPortion', 'Site\ProductsController@getPortion')->name('product.getPortion');
    Route::get('products/getPriceRange', 'Site\ProductsController@getPriceRange')->name('product.getPriceRange');
    Route::get(r('/products/category') . '/{url}', 'Site\AppController@productsCategoryList')->name('products.category.list');

    Route::post('/change-increment', [\App\Http\Controllers\Site\Cabinet\OrdersController::class, 'changeOrderIncrement'])->name('changeIncrement');

    Route::post('/product/newComment', 'Site\AppController@addComment')->name('product.otziv');

    Route::post(r('/products/filters/checking'), 'Site\FilterCheckingController@checking')->name('filters.checking');
    Route::get(r('/products/search'), 'Site\ProductsController@search')->name('products.search');
    Route::get(r('/support'), 'Site\AppController@support')->name('support');
    Route::get('boutiques/{alias}', 'Site\AppController@boutiquesView')->name('boutiques.view');
    Route::get('info', 'Site\AppController@info')->name('info');
    Route::get('personal_info', 'Site\AppController@personal_info')->name('personal_info');
    Route::get('personal_basket', 'Site\AppController@personal_basket')->name('personal_basket');
    Route::post('contact/sendMessage', 'Site\AppController@sendContactMessage')->middleware('throttle:30,1')->name('contact.sendMessage');

    Route::get('personal', 'Site\AppController@personal');
    Route::get('personal2', 'Site\AppController@personal2');
    Route::get('personal3', 'Site\AppController@personal3');
    Route::get('order_end', 'Site\AppController@order_end');
    Route::get('support1', 'Site\AppController@support1');

    Route::post('order/delete/basket', 'Site\Cabinet\OrdersController@delFromBasket')->name('del.order.bask');

    Route::group(['prefix' => 'cabinet', 'middleware' => ['logged_in']], function () {
        Route::middleware(['emailVerified', 'phoneVerified'])->group(function () {
            Route::get('profile/settings', 'Site\Cabinet\ProfileController@settings')->name('cabinet.profile');
            Route::post('/user/messages', 'Site\Cabinet\UserMessagesController@add')->name('cabinet.userMessages');
            Route::post('profile/settings/update', 'Site\Cabinet\ProfileController@updateUserInfo')->name('cabinet.profile.updateUserInfo');
            Route::get('profile/support', 'Site\Cabinet\ProfileController@support')->name('cabinet.profile.support');
            Route::get('profile/favorites', 'Site\Cabinet\ProfileController@favorites')->name('cabinet.profile.favorite');

            Route::post('/changeEmail', 'Site\Cabinet\ProfileController@changeEmail')->name('change.email');

            Route::post('payment', 'Site\Cabinet\PaymentController@payment')->name('cabinet.payment');
            Route::get('paymentCheck', 'Site\Cabinet\PaymentController@paymentCheck')->name('cabinet.paymentCheck');


            Route::post('profile/reviews/add', 'Site\Cabinet\ReviewsController@add')->name('user.review.add');
            Route::get('profile/orders/history', 'Site\Cabinet\ProfileController@ordersHistory')->name('cabinet.profile.orders.history');
            Route::get('profile/orders/active', 'Site\Cabinet\ProfileController@activeOrders')->name('cabinet.profile.orders.active');
            Route::get('profile/basket', 'Site\Cabinet\ProfileController@basket')->name('cabinet.profile.basket');
//            Route::get('orders/pending', 'Site\Cabinet\OrdersController@pending')->name('cabinet.orders.pending');
            Route::get('orders/create', 'Site\Cabinet\OrdersController@createOrder')->name('cabinet.order.create');
            Route::post('orders/naselionniPunk', 'Site\Cabinet\OrdersController@naselionniPunk')->name('cabinet.order.naselionniPunk');

            Route::post('orders/new-order', 'Site\Cabinet\OrdersController@addOrder')->name('cabinet.order.newOrder');
            Route::post('orders/new-order/deliver', 'Site\Cabinet\OrdersController@addOrderDeliver')->name('cabinet.order.newOrder.deliver');


            Route::post('orders/submit', 'Site\Cabinet\OrdersController@submitOrder')->name('cabinet.order.submit');


//            Route::get('orders/accepted', 'Site\Cabinet\OrdersController@accepted')->name('cabinet.orders.accepted');
//            Route::get('orders/declined', 'Site\Cabinet\OrdersController@declined')->name('cabinet.orders.declined');
            Route::get('order/{id}', 'Site\Cabinet\OrdersController@order')->name('cabinet.order');


            //Route::get('basket', 'Site\Cabinet\BasketController@basket')->name('cabinet.basket');
        });
        Route::any('phone/set', 'Site\Cabinet\ProfileController@setPhone')->name('cabinet.phoneVerification.setPhone');
        Route::get('phone/verify', 'Site\Cabinet\ProfileController@showPhoneVerify')->name('cabinet.phoneVerification.notice');
        Route::post('phone/verify', 'Site\Cabinet\ProfileController@verify')->name('cabinet.phoneVerification.verify');

        Route::post('phone/change/code', 'Site\Cabinet\ProfileController@sendPhoneChangingCode')->name('cabinet.phoneVerification.sendPhoneChangingCode');
        Route::post('phone/change', 'Site\Cabinet\ProfileController@phoneChange')->name('cabinet.phoneVerification.change');

        Route::post('email/change/code', 'Site\Cabinet\ProfileController@sendEmailChangingCode')->name('cabinet.emailVerification.sendEmailChangingCode');
        Route::post('email/change', 'Site\Cabinet\ProfileController@emailChange')->name('cabinet.emailVerification.change');

        Route::get('email/notice', 'Site\Cabinet\ProfileController@showEmailVerify')->name('cabinet.emailVerification.notice');
        Route::post('email/resend', 'Site\Cabinet\ProfileController@resendVerificationEmail')->name('cabinet.emailVerification.resend');

        Route::get('favorites/list', 'Site\Cabinet\FavoritesController@getFavorites')->name('user.favorite.get');
        Route::post('favorites/add', 'Site\Cabinet\FavoritesController@add')->name('user.favorite.add');
        Route::delete('favorites/remove', 'Site\Cabinet\FavoritesController@destroy')->name('user.favorite.destroy');

    });
    Route::post('/user/messagesSent', 'Site\Cabinet\UserMessagesController@add')->name('userMessagesSend');


    Route::group(['prefix' => 'company', 'middleware' => ['logged_in', 'is_company']], function () {
        Route::get('profile', 'Site\Cabinet\Company\CabinetProfileController@main')->name('company.profile');
        Route::get('support', 'Site\Cabinet\Company\CabinetProfileController@support')->name('company.support');
        Route::get('settings', 'Site\Cabinet\Company\CabinetProfileController@settings')->name('company.settings');
        Route::match(['get', 'post'], 'import/{page?}', 'Site\Cabinet\Company\ImportController@render')->name('company.import');
        Route::match(['get', 'post'], 'images/import', 'Site\Cabinet\Company\ImportImagesController@import')->name('company.import.images');
        Route::get('export/CategoryAndFilterExport', 'Site\Cabinet\Company\CompanyItemController@export')->name('company.CategoryAndFilterExport');
        Route::get('filterAndCategory/view/{id}', 'Site\Cabinet\Company\ImportController@view')->name('company.filterAndCategory.view');
        Route::post('company/user/messages', 'Site\Cabinet\Company\UserMessagesController@add')->name('company.userMessages');

        //packages
        Route::get('profile/packages', 'Site\Cabinet\Company\CompanyPackagesController@view')->name('company.packages.view');
        Route::get('profile/packages/buy/{id}', 'Site\Cabinet\Company\CompanyPackagesController@buy')->name('company.packages.buy');
        //packages end


        //one-time-payment
        Route::get('profile/one-time-payment', 'Site\Cabinet\Company\CompanyOneTimePaymentController@view')->name('company.one-time-payment.view');
        Route::get('profile/one-time-payment/buy/{id}', 'Site\Cabinet\Company\CompanyOneTimePaymentController@buy')->name('company.one-time-payment.buy');
        //one-time-payment end


        Route::get('statisticAndRevolutions/view', 'Site\Cabinet\Company\CabinetProfileController@statisticAndRevolutions')->name('company.statisticAndRevolutions.view');

        Route::get('profile/products/list', 'Site\Cabinet\Company\CompanyItemController@list')->name('company.items.list');
        Route::get('filter/{id}', 'Site\Cabinet\Company\CompanyItemController@itemFilters')->name('company.items.filter');
        Route::post('criterions/submit/{item_id}', 'Site\Cabinet\Company\CompanyItemController@itemCriterionSubmit')->name('company.criterion.submit');
        Route::post('profile/products/delete', 'Site\Cabinet\Company\CompanyItemController@delete')->name('company.items.delete');

        Route::get('profile/products/add', 'Site\Cabinet\Company\CompanyItemController@add')->name('company.items.add');
        Route::get('profile/products/edit/{id}', 'Site\Cabinet\Company\CompanyItemController@edit')->name('company.items.edit');
        Route::get('profile/products/reviews/{id}', 'Site\Cabinet\Company\ReviewItemController@main')->name('company.items.reviews.main');
        Route::get('profile/products/reviews/view/{id}', 'Site\Cabinet\Company\ReviewItemController@view')->name('company.items.reviews.view');
        Route::post('profile/products/add_put', 'Site\Cabinet\Company\CompanyItemController@add_put')->name('company.items.add_put');
        Route::post('profile/products/edit_put/{id}', 'Site\Cabinet\Company\CompanyItemController@edit_put')->name('company.items.edit_put');
        Route::get('profile/orders/history', 'Site\Cabinet\Company\CabinetProfileController@ordersHistory')->name('company.profile.orders.history');
        Route::get('profile/orders/pending', 'Site\Cabinet\Company\CabinetProfileController@ordersPending')->name('company.profile.orders.pending');
        Route::get('profile/orders/declined', 'Site\Cabinet\Company\CabinetProfileController@ordersDeclined')->name('company.profile.orders.declined');

        Route::get('{gallery}/{id?}', 'Admin\GalleriesController@show')->name('company.gallery');
        Route::put('add', 'Admin\GalleriesController@add')->name('company.gallery.add');
        Route::patch('edit', 'Admin\GalleriesController@edit')->middleware('ajax')->name('company.gallery.edit');
        Route::patch('sort', 'Admin\GalleriesController@sort')->middleware('ajax')->name('company.gallery.sort');
        Route::post('delete', 'Admin\GalleriesController@delete')->name('company.gallery.delete');

    });

    Route::get('{url?}', 'Site\AppController@pageManager')->name('page');

    Route::post('/newMessage', 'Site\AppController@newMessage')->name('newMessage');

});


/** ajax in_home */
Route::post('/admin/items/categories/list/in_home', [\App\Http\Controllers\Admin\CategoriesController::class, 'in_home'])->name('in_home');
//Auth::routes();

//Route::get('/home', 'HomeController@index')->name('home');

Route::prefix('social-auth')->middleware('guest')->group(function (Router $router) {
    $router->get('mailru/login', 'SocialAuthController@loginViaMailru')
        ->name('socialAuth.mailru.login');
    $router->get('mailru/webhook', 'SocialAuthController@mailruWebHook')
        ->name('socialAuth.mailru.return');

    $router->get('google/login', 'SocialAuthController@loginViaGoogle')
        ->name('socialAuth.google.login');
    $router->get('google/webhook', 'SocialAuthController@googleWebHook')
        ->name('socialAuth.google.return');

    $router->get('facebook/login', 'SocialAuthController@loginViaFacebook')
        ->name('socialAuth.facebook.login');
    $router->get('facebook/webhook', 'SocialAuthController@facebookWebHook')
        ->name('socialAuth.facebook.return');
});

