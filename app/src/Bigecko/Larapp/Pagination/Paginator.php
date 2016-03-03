<?php

namespace Bigecko\Larapp\Pagination;

use Illuminate\Support\Facades\Input;

class Paginator extends \Illuminate\Pagination\Paginator
{
    public function links($view = null)
    {
        $query = $this->appends(Input::except(array('page')));
        return $query->env->getPaginationView($this, $view);
    }
} 