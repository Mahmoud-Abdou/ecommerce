<?php

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

Auth::routes();

Route::get('privacy', function () {
    return view('privacy', ['name' => 'privacy']);
});
Route::get('support', function () {
    return view('support', ['name' => 'support']);
});

Route::get('get-product-orders', 'AppSettingController@get_product_orders');
Route::get('get-product-images', 'ProductController@getProductImages');

Route::get('setlang/{lang?}', 'AppSettingController@setlang');

Route::get('payments/paypal', 'PayPalController@index')->name('paypal.index');
Route::get('payments/paypal/express-checkout-success', 'PayPalController@getExpressCheckoutSuccess');
Route::get('payments/paypal/express-checkout', 'PayPalController@getExpressCheckout')->name('paypal.express-checkout');

Route::get('firebase/sw-js', 'AppSettingController@initFirebase');

Route::get('login/{service}', 'Auth\LoginController@redirectToProvider');
Route::get('login/{service}/callback', 'Auth\LoginController@handleProviderCallback');

Route::get('storage/app/public/{id}/{conversion}/{filename?}', 'UploadController@storage');
Route::middleware('auth')->group(function () {
    Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');
    Route::get('/', 'UserController@profile')->name('users.profile');

    Route::get('users/profile', 'UserController@profile')->name('users.profile');
    Route::get('dashboard', 'DashboardController@index')->name('dashboard');

    Route::group(['middleware' => ['permission:medias']], function () {
        Route::post('uploads/store', 'UploadController@store')->name('medias.create');
        Route::get('uploads/all/{collection?}', 'UploadController@all');
        Route::get('uploads/collectionsNames', 'UploadController@collectionsNames');
        Route::post('uploads/clear', 'UploadController@clear')->name('medias.delete');
        Route::get('medias', 'UploadController@index')->name('medias');
        Route::get('uploads/clear-all', 'UploadController@clearAll');
    });

    Route::group(['middleware' => ['permission:permissions.edit']], function () {
        Route::get('permissions/role-has-permission', 'PermissionController@roleHasPermission');
        Route::get('permissions/refresh-permissions', 'PermissionController@refreshPermissions');
    });
    Route::group(['middleware' => ['permission:permissions.update']], function () {
        Route::post('permissions/give-permission-to-role', 'PermissionController@givePermissionToRole');
        Route::post('permissions/revoke-permission-to-role', 'PermissionController@revokePermissionToRole');
    });
    Route::group(['middleware' => ['permission:app-settings']], function () {
        Route::prefix('settings')->group(function () {
            Route::resource('permissions', 'PermissionController');
            Route::resource('roles', 'RoleController');
            Route::resource('customFields', 'CustomFieldController');
            Route::post('users/remove-media', 'UserController@removeMedia');
            Route::get('users/login-as-user/{id}', 'UserController@loginAsUser')->name('users.login-as-user');
            Route::resource('users', 'UserController');
            Route::patch('update', 'AppSettingController@update');
            Route::patch('translate', 'AppSettingController@translate');
            Route::get('sync-translation', 'AppSettingController@syncTranslation');
            Route::get('clear-cache', 'AppSettingController@clearCache');
            Route::get('check-update', 'AppSettingController@checkForUpdates');
            // disable special character and number in route params
            Route::get('/{type?}/{tab?}', 'AppSettingController@index')
                ->where('type', '[A-Za-z]*')->where('tab', '[A-Za-z]*')->name('app-settings');
        });
    });
    Route::get('all-permissions', 'PermissionController@all_permissions');
    
    Route::post('fields/remove-media', 'FieldController@removeMedia');
    Route::resource('fields', 'FieldController')->except([
            'show'
            ]);
            
    Route::post('markets/remove-media', 'MarketController@removeMedia');
    Route::resource('markets', 'MarketController')->except([
        // 'show'
    ]);
                
    Route::post('categories/remove-media', 'CategoryController@removeMedia');
    Route::get('categories/subcat/{id?}', 'CategoryController@subcat');
    Route::resource('categories', 'CategoryController')->except([
        'show'
        ]);
    
    Route::get('get-categories', 'CategoryController@get_categories');
    
    Route::resource('category_translation', 'CategoryTranslateController')->except([
        'show'
        ]);

    Route::resource('promoCodes', 'PromoCodeController')->except([
        'show'
        ]);

    Route::post('changeLanguage', 'AppSettingController@changeLanguage');
    Route::get('get-languages', 'AppSettingController@get_languages');

    Route::resource('faqCategories', 'FaqCategoryController')->except([
            'show'
            ]);
            
    Route::resource('orderStatuses', 'OrderStatusController')->except([
    'create', 'store', 'destroy'
    ]);
    ;
                
    Route::post('products/remove-media', 'ProductController@removeMedia');
    Route::resource('products', 'ProductController')->except([
    'show'
    ]);
    Route::get('all-products', 'ProductController@all_products');

    Route::post('product_import', 'ProductController@import');
    Route::post('category_import', 'CategoryController@import');

    Route::resource('product_translation', 'ProductTranslateController')->except([
        'show'
        ]);
  
    Route::get('get-sub-category', 'SolidController@get_sub_category');
        
    Route::post('galleries/remove-media', 'GalleryController@removeMedia');
    Route::resource('galleries', 'GalleryController')->except([
        'show'
    ]);

    Route::resource('productReviews', 'ProductReviewController')->except([
        'show'
    ]);

    Route::post('options/remove-media', 'OptionController@removeMedia');
    

    Route::resource('payments', 'PaymentController')->except([
        'create', 'store','edit', 'destroy'
    ]);
    ;

    Route::resource('faqs', 'FaqController')->except([
        'show'
    ]);
    Route::resource('marketReviews', 'MarketReviewController')->except([
        'show'
    ]);

    Route::resource('favorites', 'FavoriteController')->except([
        'show'
    ]);

    Route::resource('orders', 'OrderController');

    Route::resource('notifications', 'NotificationController')->except([
        // 'create', 'store', 'update','edit',
    ]);
    ;

    Route::resource('carts', 'CartController')->except([
        'show','store','create'
    ]);
    Route::resource('currencies', 'CurrencyController')->except([
        'show'
    ]);
    Route::resource('deliveryAddresses', 'DeliveryAddressController')->except([
        'show'
    ]);

    Route::resource('drivers', 'DriverController')->except([
        'show','edit','update'
    ]);

    Route::resource('earnings', 'EarningController')->except([
        'show','edit','update'
    ]);

    Route::resource('driversPayouts', 'DriversPayoutController')->except([
        'show','edit','update'
    ]);

    Route::resource('marketsPayouts', 'MarketsPayoutController')->except([
        'show','edit','update'
    ]);

    Route::resource('optionGroups', 'OptionGroupController')->except([
        'show'
    ]);

    Route::post('options/remove-media', 'OptionController@removeMedia');

    Route::resource('options', 'OptionController')->except([
        'show'
    ]);
    Route::get('new-categories', 'CategoryController@newCategories');

    Route::post('send-message', 'ChatController@sendmessage');
    Route::get('get-message', 'ChatController@getmessage');
    Route::get('chat-page', 'ChatController@chat_page');
    Route::get('get-chat-list', 'ChatController@get_chat_list');
    Route::get('test-sendwebmessage', 'ChatController@test');
    Route::get('firebase/FLUTTER_NOTIFICATION_CLICK', 'ChatController@chat_page');
    Route::get('check_role/{permission_id?}', 'PermissionController@check_role');
});
    Route::get('testnot', 'SolidController@test');

    Route::get('public1', 'SolidController@public');
    Route::get('testnot', 'SolidController@test');
    Route::get('get-roles', 'PermissionController@get_roles');
