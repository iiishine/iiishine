<?php namespace Bigecko\Larapp\Models;

use Illuminate\Database\Eloquent\Model;

abstract class BaseModel extends Model
{
    public $throwOnValidation = true;

}
