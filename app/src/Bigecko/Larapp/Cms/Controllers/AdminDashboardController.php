<?php
namespace Bigecko\Larapp\Cms\Controllers;

use Illuminate\Support\Facades\View;

class AdminDashboardController extends AdminController {

    public function index()
    {
        return View::make('grcms::admin.dashboard');
    }
} 