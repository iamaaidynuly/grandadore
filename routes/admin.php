<?php

use Illuminate\Support\Facades\Route;

//region CKFinder
Route::any('file_browser/connector', '\CKSource\CKFinderBridge\Controller\CKFinderController@requestAction')->name('ckfinder_connector');
Route::any('file_browser/browser', '\CKSource\CKFinderBridge\Controller\CKFinderController@browserAction')->name('ckfinder_browser');
//endregion

Route::name('admin.')->namespace('Admin')->group(function () {
    //region Logout
    Route::post('logout', 'AuthController@logout')->name('logout');
    //endregion
    //region Home Page Redirect
    Route::get('/', 'AuthController@redirectIfAuthenticated');
    //endregion
    //region Dashboard
//    Route::get('dashboard', 'DashboardController@main')->name('dashboard');
    //endregion
    //region Languages
    Route::prefix('languages')->name('languages.')->group(function () {
        $c = 'LanguagesController@';
        Route::get('', $c . 'main')->name('main');
        Route::patch('', $c . 'editLanguage');
        Route::patch('sort', $c . 'sort')->middleware('ajax')->name('sort')->middleware('admin:manager.admin');
    });
    //endregion
    //region Pages
    Route::prefix('pages')->name('pages.')->group(function () {
        $c = 'PagesController@';
        Route::get('', $c . 'main')->name('main')->middleware('admin:manager.admin');
        Route::get('add', $c . 'addPage')->name('add')->middleware('admin:manager.admin');
        Route::put('add', $c . 'addPage_put')->middleware('admin:manager.admin');
        Route::get('edit/{id}', $c . 'editPage')->name('edit')->middleware('admin:manager.admin');
        Route::patch('edit/{id}', $c . 'editPage_patch')->middleware('admin:manager.admin');
        Route::delete('delete', $c . 'deletePage_delete')->middleware('ajax')->name('delete')->middleware('admin:manager.admin');
        Route::patch('sort', $c . 'sort')->middleware('ajax')->name('sort')->middleware('admin:manager.admin');
    });
    //endregion
    Route::match(['get', 'post'], 'banners/{page}', 'BannersController@renderPage')->name('banners')->middleware('admin:manager.admin');

    Route::prefix('home')->name('home.')->group(function () {
        $c = 'HomeInfoController@';
        Route::get('', $c . 'main')->name('main')->middleware('admin:manager.admin');
        Route::get('add', $c . 'add')->name('add')->middleware('admin:manager.admin');
        Route::put('add', $c . 'add_put')->middleware('admin:manager.admin');
        Route::get('edit/{id}', $c . 'edit')->name('edit')->middleware('admin:manager.admin');
        Route::patch('edit/{id}', $c . 'edit_patch')->middleware('admin:manager.admin');
        Route::delete('delete', $c . 'delete')->middleware('ajax')->name('delete')->middleware('admin:manager.admin');
    });

    Route::prefix('short_links')->name('short_links.')->group(function () {
        $c = 'ShortLinksController@';
        Route::get('', $c . 'main')->name('main')->middleware('admin:manager.admin');
        Route::get('add', $c . 'add')->name('add')->middleware('admin:manager.admin');
        Route::put('add', $c . 'add_put')->middleware('admin:manager.admin');
        Route::get('edit/{id}', $c . 'edit')->name('edit')->middleware('admin:manager.admin');
        Route::patch('edit/{id}', $c . 'edit_patch')->middleware('admin:manager.admin');
        Route::delete('delete', $c . 'delete')->middleware('ajax')->name('delete')->middleware('admin:manager.admin')->middleware('admin:admin');
    });
    //region Categories


    Route::get('items/categories/list/{parent_id?}', 'CategoriesController@categoriesList')->name('category.list')->middleware('admin:admin');
    Route::any('items/categories/add/{parent_id?}', 'CategoriesController@create')->name('category.add')->middleware('admin:admin');
    Route::any('items/categories/edit/{id}', 'CategoriesController@update')->name('category.edit')->middleware('admin:admin');
    Route::get('items/categories/delete/{id}', 'CategoriesController@delete')->middleware('admin:admin');
    Route::get('items/categories/change/parent/{id}/{parent_id}', 'CategoriesController@changeParent')->middleware('admin:admin');
    Route::post('items/categories/change/order', 'CategoriesController@changeOrder')->middleware('admin:admin');
    Route::get('items/categories/children/get/{id}', 'CategoriesController@getChildren')->middleware('admin:admin');
    Route::post('items/categories/children/getMultiple', 'CategoriesController@getMultiple')->middleware('admin:admin');
    Route::get('items/categories/deleteImage/{id}', 'CategoriesController@deleteImage')->middleware('admin:admin');
    Route::patch('items/categories/sort', 'CategoriesController@sort')->middleware('ajax')->name('categories.sort')->middleware('admin:admin');
    Route::post('items/categories/discount/add/{id}', 'CategoriesController@addDiscountToCategory')->name('add.categories.discount')->middleware('admin:admin');
    Route::get('items/categories/discount/{id}', 'CategoriesController@categoryDiscount')->name('categories.discount')->middleware('admin:admin');
    //endregion
    //region Filters
    Route::get('items/filters/list/', 'FiltersController@filtersList')->name('filters.list')->middleware('admin:admin');
    Route::any('items/filters/add/', 'FiltersController@create')->name('filters.add')->middleware('admin:admin');
    Route::any('items/filters/edit/{id}', 'FiltersController@update')->name('filters.edit')->middleware('admin:admin');
    Route::get('items/filters/delete/{id}', 'FiltersController@delete')->name('filters.delete')->middleware('admin:admin');
    Route::get('items/filters/criterion/delete/{id}', 'FiltersController@deleteCriterion')->middleware('admin:admin'); //ajax
    Route::post('filters/getFilters', 'FiltersController@getFilters')->middleware('admin:admin');
    //endregion
    //region Filters
    Route::get('items/color-filters/list', 'ColorFiltersController@filtersList')->name('colorFilters.list')->middleware('admin:admin');
    Route::any('items/color-filters/add', 'ColorFiltersController@create')->name('colorFilters.add')->middleware('admin:admin');
    Route::any('items/color-filters/edit/{id}', 'ColorFiltersController@update')->name('colorFilters.edit')->middleware('admin:admin');
    Route::get('items/color-filters/delete/{id}', 'ColorFiltersController@delete')->name('colorFilters.delete')->middleware('admin:admin');
    Route::patch('items/color-filters/sort', 'ColorFiltersController@sort')->middleware('ajax')->name('colorFilters.sort')->middleware('admin:admin');

    Route::post('filters/getFilters', 'FiltersController@getFilters')->middleware('admin:admin');
    //endregion
    //region FilterCategory
    Route::any('items/filters/filterCategory/{id?}', 'FilterCategoryController@filterCategory')->name('filters.filterCategory')->middleware('admin:admin');

    //endregion
    //region Items
    Route::prefix('items')->name('items.')->group(function () {
        $c = 'ItemsController@';
        Route::get('import/downloadExample', 'ImportController@downloadExample')->name('import.downloadExample');
        Route::match(['get', 'post'], 'import/{page?}', 'ImportController@render')->name('import');
        Route::match(['get', 'post'], 'images/import', 'ImportImagesController@import')->name('import.images');
        Route::get('filterAndCategory/view/{id}', 'ImportController@view')->name('filterAndCategory.view');

        Route::get('add', $c . 'addItem')->name('add')->middleware('admin:admin');
        Route::get('/{id?}', $c . 'index')->name('index')->middleware('admin:moderator.admin');
        Route::get('filter/{id}', $c . 'itemFilters')->name('filter')->middleware('admin:moderator.admin');
        Route::post('criterions/submit/{item_id}', $c . 'itemCriterionSubmit')->name('criterion.submit')->middleware('admin:moderator.admin');
        Route::post('add', $c . 'addSave')->name('add.save')->middleware('admin:admin');
        Route::get('edit/{id}', $c . 'editItem')->name('edit')->middleware('admin:moderator.admin');
        Route::post('edit/status', $c . 'itemChangeStatus')->name('edit.status')->middleware('admin:moderator.admin');
        Route::get('moderate/{id?}/{page?}', $c . 'ModerateItem')->name('moderate')->middleware('admin:moderator.admin');
        Route::post('moderate_many', $c . 'ModerateMany')->name('moderate.many')->middleware('admin:moderator.admin');
        Route::post('edit/{id}', $c . 'editSave')->name('edit.save')->middleware('admin:moderator.admin');
        Route::delete('delete', $c . 'delete')->middleware('ajax')->name('delete')->middleware('admin:admin');;
        Route::patch('sort', $c . 'sort')->middleware('ajax')->name('sort')->middleware('admin:moderator.admin');

    });
    //endregion
    //region Slider
    Route::prefix('main-slider')->name('main_slider.')->group(function () {
        $c = 'MainSliderController@';
        Route::get('', $c . 'main')->name('main');
        Route::get('add', $c . 'add')->name('add');
        Route::put('add', $c . 'add_put');
        Route::get('edit/{id}', $c . 'edit')->name('edit');
        Route::patch('edit/{id}', $c . 'edit_patch');
        Route::delete('delete', $c . 'delete')->middleware('ajax')->name('delete');
        Route::patch('sort', $c . 'sort')->middleware('ajax')->name('sort');
    });
    //endregion
    //region Support
    Route::prefix('support')->name('support.')->group(function () {
        $c = 'SupportController@';
        Route::get('', $c . 'main')->name('main');
        Route::get('add', $c . 'add')->name('add');
        Route::put('add', $c . 'add_put');
        Route::get('edit/{id}', $c . 'edit')->name('edit');
        Route::patch('edit/{id}', $c . 'edit_patch');
        Route::delete('delete', $c . 'delete')->middleware('ajax')->name('delete');
        Route::patch('sort', $c . 'sort')->middleware('ajax')->name('sort');
    });
    //endregion
    //region News
    Route::prefix('news')->name('news.')->group(function () {
        $c = 'NewsController@';
        Route::get('', $c . 'main')->name('main');
        Route::get('add', $c . 'add')->name('add');
        Route::put('add', $c . 'add_put');
        Route::get('edit/{id}', $c . 'edit')->name('edit');
        Route::patch('edit/{id}', $c . 'edit_patch');
        Route::delete('delete', $c . 'delete')->middleware('ajax')->name('delete');
        Route::patch('sort', $c . 'sort')->middleware('ajax')->name('sort');
    });
    //endregion
    //region Brands
    Route::prefix('brands')->name('brands.')->group(function () {
        $c = 'BrandsController@';
        Route::get('', $c . 'main')->name('main');
        Route::get('add', $c . 'add')->name('add');
        Route::put('add', $c . 'add_put');
        Route::get('edit/{id}', $c . 'edit')->name('edit');
        Route::patch('edit/{id}', $c . 'edit_patch');
        Route::delete('delete', $c . 'delete')->middleware('ajax')->name('delete');
        Route::patch('sort', $c . 'sort')->middleware('ajax')->name('sort');
    });
    //endregion
    //region Packages
    Route::prefix('packages')->name('packages.')->group(function () {
        $c = 'PackagesController@';
        Route::get('', $c . 'main')->name('main')->middleware('admin:admin');;
        Route::get('add', $c . 'add')->name('add')->middleware('admin:admin');;
        Route::put('add', $c . 'add_put')->middleware('admin:admin');;
        Route::get('edit/{id}', $c . 'edit')->name('edit')->middleware('admin:admin');;
        Route::patch('edit/{id}', $c . 'edit_patch')->middleware('admin:admin');;
        Route::delete('delete', $c . 'delete')->middleware('ajax')->name('delete')->middleware('admin:admin');;
        Route::patch('sort', $c . 'sort')->middleware('ajax')->name('sort')->middleware('admin:admin');;
    });
    //endregion
    //region One-time payment
    Route::prefix('one-time-payment')->name('one-time-payment.')->group(function () {
        $c = 'OneTimePaymentController@';
        Route::get('', $c . 'main')->name('main')->middleware('admin:admin');;
        Route::get('add', $c . 'add')->name('add')->middleware('admin:admin');;
        Route::put('add', $c . 'add_put')->middleware('admin:admin');;
        Route::get('edit/{id}', $c . 'edit')->name('edit')->middleware('admin:admin');;
        Route::patch('edit/{id}', $c . 'edit_patch')->middleware('admin:admin');;
        Route::delete('delete', $c . 'delete')->middleware('ajax')->name('delete')->middleware('admin:admin');;
        Route::patch('sort', $c . 'sort')->middleware('ajax')->name('sort')->middleware('admin:admin');;
    });
    //endregion
    //region Reviews
    Route::prefix('reviews')->name('reviews.')->group(function () {
        $c = 'ReviewsController@';
        Route::get('{id?}', $c . 'main')->name('main');
        Route::get('moderate/{id}', $c . 'moderateItem')->name('moderate');
        Route::get('view/{id}', $c . 'view')->name('view');
        Route::delete('delete', $c . 'delete')->middleware('ajax')->name('delete')->middleware('admin:admin');
        Route::patch('sort', $c . 'sort')->middleware('ajax')->name('sort');
    });
    //endregion
    //region User messages
    Route::prefix('user_messages')->name('user_messages.')->group(function () {
        $c = 'UserMessagesController@';
        Route::get('{id?}', $c . 'main')->name('main');

        Route::get('/view/{id}', $c . 'view')->name('view');
        Route::delete('delete', $c . 'delete')->middleware('ajax')->name('delete')->middleware('admin:admin');
    });
    //endregion
    //region Galleries
    Route::prefix('gallery')->group(function () {
        $c = 'GalleriesController@';
        Route::get('{gallery}/{key?}', $c . 'show')->name('gallery');
        Route::put('add', $c . 'add')->name('gallery.add');
        Route::patch('edit', $c . 'edit')->middleware('ajax')->name('gallery.edit');
        Route::patch('sort', $c . 'sort')->middleware('ajax')->name('gallery.sort');
        Route::delete('delete', $c . 'delete')->middleware('ajax')->name('gallery.delete');
    });
    //endregion
    //region Users
    Route::prefix('users')->name('users.')->group(function () {
        $c = 'UsersController@';
        Route::get('type/{role?}', $c . 'main')->name('main')->middleware('admin:moderator.admin');
        Route::get('edit/{id}', 'UsersController@edit')->name('edit');
        Route::post('update/{id}', 'UsersController@update')->name('update');
        Route::get('company/package/{id}', $c . 'packagesEdit')->name('package.edit')->middleware('admin:admin');
        Route::post('company/package/submit/{id}', $c . 'packagesEditSubmit')->name('packages.submit')->middleware('admin:admin');
        //Route::get('/magazine', $c . 'magazine')->name('view.magazine')->middleware('admin:moderator.admin');
        Route::get('/accept/{id}', $c . 'acceptEmail')->name('accept.email')->middleware('admin:admin');
        Route::get('view/{id}', $c . 'view')->name('view')->middleware('admin:moderator.admin');
        Route::get('add/{type}', $c . 'addUserByType')->name('add')->middleware('admin:admin');
        Route::post('add/{type}', $c . 'addUserByType')->name('add_put')->middleware('admin:admin');
        Route::get('add/admins/{role}', $c . 'addAdminsByType')->name('add.admin')->middleware('admin:admin');
        Route::post('add/admins/put/{role}', $c . 'addAdminsByType')->name('add_put.admin')->middleware('admin:admin');
        Route::patch('toggle-active', $c . 'toggleActive')->name('toggleActive')->middleware('admin:moderator.admin');
        Route::get('statistics', $c . 'statistics')->name('statistics')->middleware('admin:moderator.admin');
        Route::delete('delete', $c . 'delete')->middleware('ajax')->name('delete')->middleware('admin:admin');
    });
    //endregion
    //region Video Galleries
    Route::prefix('video-gallery')->group(function () {
        $c = 'VideoGalleriesController@';
        Route::get('{gallery}/{key?}', $c . 'show')->name('video_gallery');
        Route::get('{gallery}/add/{key?}', $c . 'add')->name('video_gallery.add');
        Route::put('{gallery}/add/{key?}', $c . 'add_put');
        Route::get('{id}/edit', $c . 'edit')->name('video_gallery.edit');
        Route::patch('{key}/edit', $c . 'edit_patch');
        Route::patch('sort', $c . 'sort')->middleware('ajax')->name('video_gallery.sort');
        Route::delete('delete', $c . 'delete')->middleware('ajax')->name('video_gallery.delete');
    });
    //endregion
    //region Profile
    Route::prefix('profile')->name('profile.')->group(function () {
        $c = 'ProfileController@';
        Route::get('', $c . 'main')->name('main');
        Route::patch('', $c . 'patch');
    });
    //endregion
    //region Translations
    Route::prefix('translations')->name('translations.')->group(function () {
        $c = 'TranslationsController@';
        Route::get('{locale}', $c . 'main')->name('main');
        Route::get('{locale}/{filename}', $c . 'edit')->name('edit');
        Route::patch('{locale}/{filename}', $c . 'edit_patch')->name('edit');
    });
    //endregion
    //region Orders
    Route::prefix('orders')->name('orders.')->group(function () {
        $c = 'OrdersController@';


        Route::get('new', $c . 'newOrders')->name('new')->middleware('admin:admin.moderator');
        Route::get('pending', $c . 'pendingOrders')->name('pending')->middleware('admin:admin.moderator');
        Route::get('call', $c . 'call')->name('call')->middleware('admin:admin.moderator');
        Route::get('done', $c . 'doneOrders')->name('done')->middleware('admin:admin.moderator');
        Route::get('declined', $c . 'declinedOrders')->name('declined')->middleware('admin:admin.moderator');
        /** bistri zakaz  */
        Route::get('sell', $c . 'bistriZakaz')->name('bistri')->middleware('admin:admin.moderator');
        /** end bistri zakaz */
        Route::get('view/{id}', $c . 'view')->name('view')->middleware('admin:admin.moderator');
        Route::get('view-order/{id}', $c . 'viewOrder')->name('viewFast')->middleware('admin:admin.moderator');

        /** zakazatzvonok delete */

        Route::post('/call/delete',$c.'dropZakazatZvonok')->name('delete-call')->middleware('admin:admin.moderator');

        Route::post('view-order/change-status', $c . 'ajaxChangeStatus')->name('changeStatus')->middleware('admin:admin.moderator');
        Route::delete('delete', $c . 'delete')->name('clear')->middleware('admin:admin');
        Route::patch('respond/{id}', $c . 'respond')->name('respond')->middleware('admin:admin.moderator');
        Route::patch('change-process/{id}', $c . 'changeProcess')->name('change_process')->middleware('admin:admin.moderator');
        Route::get('user/{id}/{status}', $c . 'userOrders')->name('user')->middleware('admin:admin');
        Route::any('export/{id}', $c . 'exportOrder')->name('export')->middleware('admin:admin');


//
//            Route::get('pending', $c.'pending')->name('pending');
//            Route::get('accepted', $c.'accepted')->name('accepted');
//            Route::get('declined', $c.'declined')->name('declined');
//            Route::get('view/{id}', $c.'view')->name('view');
//            Route::patch('respond', $c.'respond')->name('respond');
//            Route::delete('delete', $c.'delete')->middleware('ajax')->name('delete');
//            Route::delete('clear', $c.'clear')->name('clear');
    });
    //endregion
    //region Products
    Route::prefix('products')->name('products.')->group(function () {
        $c = 'ProductsController@';
        Route::get('{id}', $c . 'main')->name('main');
        Route::get('add/{id}', $c . 'add')->name('add');
        Route::put('add', $c . 'add_put')->name('add_put');
        Route::get('edit/{id}', $c . 'edit')->name('edit');
        Route::patch('edit/{id}', $c . 'edit_patch');
        Route::patch('sort', $c . 'sort')->middleware('ajax')->name('sort');
        Route::delete('delete', $c . 'delete')->middleware('ajax')->name('delete');


    });
    //endregion
    //region Pickup Points
    Route::prefix('pickup-points')->name('pickup_points.')->group(function () {
        $c = 'PickupPointsController@';
        Route::get('', $c . 'main')->name('main');
        Route::get('add', $c . 'add')->name('add');
        Route::put('add', $c . 'add_put');
        Route::get('edit/{id}', $c . 'edit')->name('edit');
        Route::patch('edit/{id}', $c . 'edit_patch');
        Route::delete('delete', $c . 'delete')->middleware('ajax')->name('delete')->middleware('admin:admin');
        Route::patch('sort', $c . 'sort')->middleware('ajax')->name('sort');
    });
    //endregion
    //region Delivery regions
    Route::prefix('delivery-regions')->name('delivery_regions.')->group(function () {
        $c = 'DeliveryRegionsController@';
        Route::get('', $c . 'main')->name('main');
        Route::get('add', $c . 'add')->name('add');
        Route::put('add', $c . 'add_put');
        Route::get('edit/{id}', $c . 'edit')->name('edit');
        Route::patch('edit/{id}', $c . 'edit_patch');
        Route::delete('delete', $c . 'delete')->middleware('ajax')->name('delete')->middleware('admin:admin');
    });
    //endregion
    Route::prefix('minimum_total_cost')->name('minimum_total_cost.')->group(function () {
        $c = 'MinimumTotalCostController@';
        Route::get('edit/{id}', $c . 'edit')->name('edit');
        Route::patch('edit/{id}', $c . 'edit_patch');
    });
    //region Delivery Cities
    Route::prefix('delivery-cities')->name('delivery_cities.')->group(function () {
        $c = 'DeliveryCitiesController@';
        Route::get('{id}', $c . 'main')->name('main');
        Route::get('add/{id}', $c . 'add')->name('add');
        Route::put('add/{id}', $c . 'add_put');
        Route::get('edit/{id}', $c . 'edit')->name('edit');
        Route::patch('edit/{id}', $c . 'edit_patch');
        Route::delete('delete', $c . 'delete')->middleware('ajax')->name('delete');
    });
    //endregion
    //region Product Options
    Route::prefix('product-options')->name('product_options.')->group(function () {
        $c = 'ProductOptionsController@';
        Route::get('', $c . 'main')->name('main');
        Route::get('add', $c . 'add')->name('add');
        Route::put('add', $c . 'add_put');
        Route::get('edit/{id}', $c . 'edit')->name('edit');
        Route::patch('edit/{id}', $c . 'edit_patch');
        Route::delete('delete', $c . 'delete')->middleware('ajax')->name('delete');
        Route::patch('sort', $c . 'sort')->middleware('ajax')->name('sort');
    });
    //endregion


    Route::get('/product/comment','ProductsController@comment')->middleware('auth')->name('comment');

    Route::post('/product/commentStatus','ProductsController@commentStatus')->middleware('auth')->name('commentStatus');

    Route::post('/otziv/delete','ProductsController@commentRemove')->middleware('auth')->name('commentRemove');


    Route::get('/product/address','ProductsController@address')->middleware('auth')->name('address');

    Route::post('/product/change2','ProductsController@changeStatus')->middleware('auth')->name('changeStatus2');

    Route::post('/product/addressRemove','ProductsController@deleteAddress')->middleware('auth')->name('addressRemove');

    Route::get('/new/address','ProductsController@addAddress')->middleware('auth')->name('addAddress');

    Route::post('/new/address/','ProductsController@createAddress')->middleware('auth')->name('createAddress');

    Route::get('/edit/address/{id}','ProductsController@editAddress')->middleware('auth')->name('editAddress');

    Route::post('/edit/pickup-address/{id}','ProductsController@editThisAddress')->middleware('auth')->name('editThisAddress');


    // search

    Route::get('/product/search','SearchController@search')->middleware('auth')->name('search');

    Route::get('/new/search','SearchController@addSearch')->middleware('auth')->name('addSearch');

    Route::post('/new/search/','SearchController@createSearch')->middleware('auth')->name('createSearch');

    Route::get('/edit/search/{id}','SearchController@editSearch')->middleware('auth')->name('editSearch');

    Route::post('/product/searchRemove','SearchController@deleteSearch')->middleware('auth')->name('searchRemove');

    Route::post('/product/change-status','SearchController@changeStatus')->middleware('auth')->name('searchChangeStatus');

    Route::post('/edit/pickup-search/{id}','SearchController@editThisSearch')->middleware('auth')->name('editThisSearch');

});
