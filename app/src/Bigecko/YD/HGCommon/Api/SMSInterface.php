<?php namespace Bigecko\YD\HGCommon\Api;

interface SMSInterface {

    public function send($phoneNumber, $message, $extra = null);

}
