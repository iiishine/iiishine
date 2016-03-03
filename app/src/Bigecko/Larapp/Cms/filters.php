<?php

Route::filter('adminauth', function() {
    if (Auth::guest()) {
        return Redirect::guest('admin/login');
    }
});