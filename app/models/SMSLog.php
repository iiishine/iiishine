<?php

use Bigecko\YD\HGCommon\Models\BaseModel;

class SMSLog extends BaseModel {

    protected $table = 'SMS_LOG';

    protected $fillable = array(
        'MOBILE', 'CLIENT_IP', 'CONTENT', 'VERIFY_CODE', 'SENT',
    );

    public static function getNumSent($mobile, $start, $end)
    {
        $model = new SMSLog();
        $query = $model->newQuery();

        return $query->where('MOBILE', $mobile)
            ->where(static::CREATED_AT, '>=', $start)
            ->where(static::CREATED_AT, '<', $end)
            ->where('SENT', 1)
            ->count();
    }

    public static function getNumByMobile($mobile, $start, $end)
    {
        $model = new SMSLog();
        $query = $model->newQuery();
        return $query->where('MOBILE', $mobile)
            ->where(static::CREATED_AT, '>=', $start)
            ->where(static::CREATED_AT, '<', $end)
            ->count();
    }
}
