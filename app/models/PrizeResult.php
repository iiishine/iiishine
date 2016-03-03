<?php

class PrizeResult extends Eloquent {

    protected $table = 'PRIZE_RESULT';

    protected $fillable = array(
        'CID', 'PRIZE_NAME', 'CDATE'
    );
    public $timestamps = false;
}
