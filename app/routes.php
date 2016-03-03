<?php

Route::group(array('before' => 'event_period'), function() {

    Route::get('/', 'HomeController@index');

    Route::get('merchants', 'HomeController@getMerchants');

    Route::get('rules', function() {
        return View::make('rules');
    });

    Route::controller('auth', 'AuthController');

    Route::controller('commonreg', 'CommonregController');

    Route::controller('smsapi', 'SmsApiController');

    Route::controller('prize', 'PrizeController');

    Route::get('point', array(
        'before' => array('wechat_auth', 'mobile_required'),
        'uses' => 'HomeController@getPoint',
    ));
});

Route::get('assetcc', function() {
    Cache::forget('larapp.assetts');
});
Route::get('dev/grpi', function() { phpinfo(); });
