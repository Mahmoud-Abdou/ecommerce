<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::prefix('driver')->group(function () {
    Route::post('login', 'API\Driver\UserAPIController@login');
    Route::post('register', 'API\Driver\UserAPIController@register');
    Route::post('send_reset_link_email', 'API\UserAPIController@sendResetLinkEmail');
    Route::get('user', 'API\Driver\UserAPIController@user');
    Route::get('logout', 'API\Driver\UserAPIController@logout');
    Route::get('settings', 'API\Driver\UserAPIController@settings');
});


    Route::post('social_login', 'API\UserAPIController@social_login');
    Route::post('login', 'API\UserAPIController@login');
    Route::post('register', 'API\UserAPIController@register');
    Route::post('send_reset_link_email', 'API\UserAPIController@sendResetLinkEmail');
    Route::get('user', 'API\UserAPIController@user');
    Route::get('logout', 'API\UserAPIController@logout');
    Route::get('settings', 'API\UserAPIController@settings');
Route::resource('fields', 'API\FieldAPIController');
Route::resource('categories', 'API\CategoryAPIController');
Route::resource('markets', 'API\MarketAPIController');

Route::resource('faq_categories', 'API\FaqCategoryAPIController');
Route::resource('products', 'API\ProductAPIController');
Route::get('searchproduct', 'API\ProductAPIController@searchproduct');
Route::resource('galleries', 'API\GalleryAPIController');
Route::resource('product_reviews', 'API\ProductReviewAPIController');


Route::resource('faqs', 'API\FaqAPIController');
Route::resource('market_reviews', 'API\MarketReviewAPIController');
Route::resource('currencies', 'API\CurrencyAPIController');

Route::resource('option_groups', 'API\OptionGroupAPIController');

Route::resource('options', 'API\OptionAPIController');



Route::middleware('auth:api')->group(function () {
    Route::group(['middleware' => ['role:driver']], function () {
        Route::prefix('driver')->group(function () {
            Route::resource('orders', 'API\OrderAPIController');
            Route::resource('notifications', 'API\NotificationAPIController');
        });
    });
    Route::group(['middleware' => ['role:admin']], function () {
        Route::prefix('manager')->group(function () {
            Route::resource('drivers', 'API\DriverAPIController');
            Route::resource('orders', 'API\OrderAPIController');
            Route::resource('order_statuses', 'API\OrderStatusAPIController');
            Route::resource('notifications', 'API\NotificationAPIController');
            Route::resource('earnings', 'API\EarningAPIController');
            Route::resource('driversPayouts', 'API\DriversPayoutAPIController');
            Route::resource('marketsPayouts', 'API\MarketsPayoutAPIController');
        });
    });
    Route::post('users/{id}', 'API\UserAPIController@update');
    Route::resource('order_statuses', 'API\OrderStatusAPIController');
    Route::get('payments/byMonth', 'API\PaymentAPIController@byMonth')->name('payments.byMonth');
    Route::resource('payments', 'API\PaymentAPIController');
    Route::get('favorites/exist', 'API\FavoriteAPIController@exist');
    Route::resource('favorites', 'API\FavoriteAPIController');
    Route::resource('orders', 'API\OrderAPIController');
    Route::resource('product_orders', 'API\ProductOrderAPIController');
    Route::resource('notifications', 'API\NotificationAPIController');
    Route::get('carts/count', 'API\CartAPIController@count')->name('carts.count');
    Route::resource('carts', 'API\CartAPIController');
    Route::resource('delivery_addresses', 'API\DeliveryAddressAPIController');
    Route::post('send-message', 'ChatController@sendmessage');
    Route::get('get-message', 'ChatController@getmessage');
});

    Route::post('updateorders', 'API\OrderAPIController@cancelorder');
    Route::get('hekaya-home', 'API\ProductAPIController@hekaya');
    Route::get('sweetjana-home', 'API\ProductAPIController@sweetJana');
    Route::get('pet-home', 'API\ProductAPIController@pethome');
    Route::get('order_statuses_get', 'API\OrderStatusAPIController@index');



Route::get('privacy', function () {
    return view('privacy', ['name' => 'privacy']);
});
Route::get('support', function () {
    return view('support', ['name' => 'support']);
});