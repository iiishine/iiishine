<?php namespace Bigecko\YD\HGCommon\Api;

use Illuminate\Support\Facades\Log;

class LogSms implements SMSInterface
{
    public function send($phoneNumber, $message, $extra = null)
    {
        Log::info("== Sms to $phoneNumber: $message", array(
            'ydapi' => true,
            'apiName' => 'SMS',
            'apiData' => array('Phone' => $phoneNumber),
        ));

        return array('error' => '0');
    }
}
