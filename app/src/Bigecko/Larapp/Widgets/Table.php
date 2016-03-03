<?php namespace Bigecko\Larapp\Widgets;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\View;

class Table extends AbstractWidget
{
    protected $options;

    public function __construct(array $options)
    {
        parent::__construct();
        $this->options = $options;
    }

    public function renderHead($view = 'larawidget::table.head')
    {
        $vars = array();
        $fields = $this->options['fields'];

        $sortField = Input::get('sort');
        $sortOrder = Input::get('order');

        foreach ($fields as $key => $field) {
            if (is_int($key) && is_string($field)) {
                $vars[] = $field;
            }
            else {
                $query = Input::except('sort', 'order');
                $query['sort'] = $key;
                $query['order'] = $sortOrder == 'desc' ? 'asc' : 'desc';
                $vars[] = array(
                    'text' => is_string($field) ? $field : $field['text'],
                    'field' => $key,
                    'active_sort' => $sortField == $key ? $sortOrder : '',
                    'url' => Input::url() . '?' . http_build_query($query),
                );
            }
        }

        return View::make($view, array('vars' => $vars));
    }
}
