<?php

namespace Bigecko\YD\Event;

use Bigecko\YD\HGCommon\Models\BaseModel;


abstract class Customer extends BaseModel
{
    protected $table = 'CUSTOMERS';

    protected $fillable = array(
        'MPHONE', 'SER_OPENID', 'MEMBER_ID', 'USER_TYPE',
    );

    public static function findByMobile($mobile)
    {
        return static::where('MPHONE', $mobile)
            ->first();
    }

    public function getDates()
    {
        return parent::getDates() + array('NEW_MEMBER', 'TIME_TO_USER', 'TIME_TO_MEMBER');
    }

    public function isMember()
    {
        return !empty($this->MEMBER_ID);
    }

    public function setWxNicknameAttribute($value)
    {
        $this->attributes['WX_NICKNAME'] = base64_encode($value);
    }

    public function getWxNicknameAttribute($value)
    {
        return base64_decode($value);
    }
}
