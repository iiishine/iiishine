<?php namespace Bigecko\YD\HGCommon\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Input;

abstract class BaseModel extends Model
{
    const CREATED_AT = 'CREATED_AT';
    const UPDATED_AT = 'UPDATED_AT';
    const DELETED_AT = 'DELETED_AT';

    protected $primaryKey = 'ID';

    public static function sortableQuery()
    {
        $model = new static();
        $query = $model->newQuery();

        $sortColumn = Input::get('sort');
        $order = Input::get('order');
        if (!empty($sortColumn)
            && Schema::hasColumn($model->getTable(), $sortColumn)
            && in_array($order, array('desc', 'asc'))) {
            $query->orderBy($sortColumn, $order);
        }

        return $query;
    }
}
