<?php

Route::model('post','Bigecko\Larapp\Cms\Models\Post');
Route::model('category', 'Bigecko\Larapp\Cms\Models\Category');

/**
 * 后台管理路由组
 */
Route::group(array(
    'prefix' => 'admin',
    'namespace' => 'Bigecko\Larapp\Cms\Controllers',
    'before' => 'adminauth',
), function() {

    Route::get('/', 'AdminDashboardController@index');

    Route::resource('post', 'PostAdminController');

    Route::resource('category', 'CategoryAdminController');
});
