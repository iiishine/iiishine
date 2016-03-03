<?php

namespace Bigecko\Larapp\Cms\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Config;

abstract class AdminController extends Controller
{

    public function __construct()
    {
        View::share('navigation', Config::get('firadmin::navigation'));
        View::share('active_menu', '');
        View::share('content', '');
        View::share('assets', Config::get('firadmin::assets'));
        View::share('title', Config::get('firadmin::title'));
        View::share('project_name', Config::get('firadmin::project_name'));
    }

} 