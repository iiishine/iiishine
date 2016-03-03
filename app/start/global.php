<?php

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Bigecko\Larapp\Validators\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;

/*
|--------------------------------------------------------------------------
| Register The Laravel Class Loader
|--------------------------------------------------------------------------
|
| In addition to using Composer, you may use the Laravel class loader to
| load your controllers and models. This is useful for keeping all of
| your classes in the "global" namespace without Composer updating.
|
 */

ClassLoader::addDirectories(array(

    app_path().'/commands',
    app_path().'/controllers',
    app_path().'/models',
    app_path().'/database/seeds',

));

/*
|--------------------------------------------------------------------------
| Application Error Logger
|--------------------------------------------------------------------------
|
| Here we will configure the error logger setup for the application which
| is built on top of the wonderful Monolog library. By default we will
| build a basic log file setup which creates a single file for logs.
|
 */

if (Config::get('app.debug')) {
    Log::useFiles(storage_path().'/logs/laravel.log');
}

$logFile = 'log-'.php_sapi_name().'.log';

Log::useDailyFiles(storage_path().'/logs/'.$logFile);

/*
|--------------------------------------------------------------------------
| Application Error Handler
|--------------------------------------------------------------------------
|
| Here you may handle any errors that occur in your application, including
| logging them or displaying custom views for specific errors. You may
| even register several error handlers to handle different types of
| exceptions. If nothing is returned, the default error view is
| shown, which includes a detailed stack trace during debug.
|
 */

App::error(function(Exception $exception, $code) {
    if ($exception instanceof ModelNotFoundException) {
        App::abort(404);
    }
    else if ($exception instanceof ValidationException) {
        return Redirect::back()->withInput()->withErrors($exception->errors());
    }
    else if ($exception instanceof HttpException) {
        if ($exception->getCode() == 200) {
            return $exception->getMessage();
        }
    }
    else if ($exception instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException) {
        Log::error('404: ' . Input::fullUrl());
    }
    else {
        Log::error($exception);
    }
});

/*
|--------------------------------------------------------------------------
| Maintenance Mode Handler
|--------------------------------------------------------------------------
|
| The "down" Artisan command gives you the ability to put an application
| into maintenance mode. Here, you will define what is displayed back
| to the user if maintenance mode is in effect for the application.
|
 */

App::down(function() {
    return Response::make("Be right back!", 503);
});

/*
|--------------------------------------------------------------------------
| Require The Filters File
|--------------------------------------------------------------------------
|
| Next we will load the filters file for the application. This gives us
| a nice separate location to store our route and application filter
| definitions instead of putting them all in the main routes file.
|
 */

require app_path().'/filters.php';

Event::listen('gkx.wx_getuser', 'Bigecko\YD\Event\Handlers\WxGetUser');

View::composer(array('html', 'prize_join'), 'Bigecko\YD\Qinzi\WechatJSCompoer');
View::composer(array('html', 'prize_join'), 'Bigecko\YD\Event\TrackingCodeComposer');

App::singleton('Bigecko\YD\HGCommon\Api\VoiceCode', function() {
    return new \Bigecko\YD\HGCommon\Api\VoiceCode(
        Config::get('eventpage.voice_code_api'),
        Config::get('eventpage.voice_code_params')
    );
});

/**
 * 短信验证
 */
App::bind('smsVerifier', function() {
    $verifier = new Bigecko\YD\HGCommon\Utils\SmsVerifier(
        App::make('Bigecko\YD\HGCommon\Api\VoiceCode'),
        App::make('session')
    );

    $verifier->smsTpl = Config::get('eventpage.sms_verify_code_message');

    return $verifier;
});

/**
 * 通用后台接口
 */
App::singleton('Bigecko\YD\HGCommon\Api\EventBackend', function() {
    $config = Config::get('eventpage.eventadmin');
    return new \Bigecko\YD\HGCommon\Api\EventBackend(
        rtrim($config['base_url'], '/') . '/api',
        $config['params']
    );
});


/**
 * cls
 */
App::singleton('Bigecko\YD\HGCommon\Api\CLS', function() {
    return new Bigecko\YD\HGCommon\Api\CLS(
        Config::get('eventpage.cls_api_url'),
        Config::get('eventpage.cls_params')
    );
});
App::singleton('cls', function() {
    return App::make('Bigecko\YD\HGCommon\Api\CLS');
});

/**
 * 短信记录api
 */
App::singleton('Bigecko\YD\HGCommon\Api\RecordFraudSms', function() {
    return new \Bigecko\YD\HGCommon\Api\RecordFraudSms(
        Config::get('eventpage.record_fraud_sms_api_url'),
        Config::get('eventpage.record_fraud_sms_params')
    );
});

/**
 * 购开心官网API
 */
App::singleton('Bigecko\YD\HGCommon\Api\Gkx', function() {
    $gkx = new \Bigecko\YD\HGCommon\Api\Gkx(
        Config::get('eventpage.gkx_api.url'),
        Config::get('eventpage.gkx_api.params')
    );

    $gkx->wechatOriginId = Config::get('eventpage.wechat.originId');

    return $gkx;
});


App::singleton('Bigecko\YD\Event\AuthManger', function() {
    $auth = new \Bigecko\YD\Event\AuthManger(App::make('session.store'), App::make('events'));
    return $auth;
});

/**
 * 微信jssdk
 */
App::singleton('Overtrue\Wechat\Js', function() {
    $wechatJs = new \Overtrue\Wechat\Js(
        Config::get('eventpage.wechat.appId'),
        Config::get('eventpage.wechat.appSecret')
    );

    return $wechatJs;
});


// 验证手机号码格式
Validator::extend('mobile', function($attribute, $value, $parameters) {
    $mobileNumber = trim($value);
    if (preg_match("/^1\d{10}$/", $mobileNumber)) {
        return true;
    }
    else {
        return false;
    }
});

// 验证图形验证码（去掉所有空格后验证）
Validator::extend('trimcaptcha', function($attribute, $value, $parameters) {
    $captcha = str_replace(' ', '', $value);
    return Captcha::check($captcha);
});
