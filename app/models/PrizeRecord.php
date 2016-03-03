<?php

use Bigecko\YD\HGCommon\Models\BaseModel;


class PrizeRecord extends BaseModel
{

    protected $table = 'PRIZE_RECORD';

    protected $fillable = array(
        'MPHONE', 'AFTER_SHARE', 'IS_MEMBER'
    );
}
