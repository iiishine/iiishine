<?php namespace Bigecko\Larapp\Widgets;

use Illuminate\Support\Facades\View;

abstract class AbstractWidget {

    public function __construct()
    {
        View::addNamespace('larawidget', __DIR__ . '/views');
    }

}

