<?php namespace Bigecko\YD\HGCommon\Models;

class MPhoneCheckResult extends BaseModel
{
    protected $table = 'MPHONE_CHECK_RESULTS';

    protected $fillable = array('MPHONE', 'RESULT');
}
