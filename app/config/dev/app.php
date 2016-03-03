<?php

$config = require __DIR__ . '/../app.php';

$config['debug'] = true;

$config['providers'][] = 'Illuminate\Workbench\WorkbenchServiceProvider';
$config['providers'][] = 'Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider';

// debug bar 设置
// 在访问url中增加debug参数任意值可以开启laravel的debug bar功能。
// 在url中增加debug=no参数可以关闭debug bar。
if (isset($_GET['debug'])) {
    $debug = $_GET['debug'];
    if ($debug == 'no' && isset($_COOKIE['laravel_debugbar'])) {
        setcookie('laravel_debugbar', '', time() - 3600, '/');
    }
    else if ($debug != 'no') {
        setcookie('laravel_debugbar', 1, time() + 3600*24 * 7, '/');
    }
}
if (isset($_COOKIE['laravel_debugbar'])) {
    $config['providers'][] = 'Barryvdh\Debugbar\ServiceProvider';
}

return $config;
