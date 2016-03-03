<?php
namespace Bigecko\Larapp\Pagination;


class Environment extends \Illuminate\Pagination\Environment
{
    public function make(array $items, $total, $perPage)
    {
        $paginator = new Paginator($this, $items, $total, $perPage);

        return $paginator->setupPaginationContext();
    }
}