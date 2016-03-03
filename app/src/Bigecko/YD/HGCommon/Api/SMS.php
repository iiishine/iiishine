<?php namespace Bigecko\YD\HGCommon\Api;

use Illuminate\Support\Facades\Log;

use Requests;

class SMS implements SMSInterface
{
    public $apiUrl;

    public $params;

    public function __construct($apiUrl = null, array $params = array())
    {
        $this->apiUrl = $apiUrl;
        $this->params = $params;
    }

    public function send($phoneNumber, $message, $extra = null)
    {
        $data = $this->params;
        $data['Phone'] = $phoneNumber;
        $data['Message'] = $message;
        if (isset($extra) && is_array($extra)) {
            $data = array_merge($data, $extra);
        }
        if (isset($data['password']) && !empty($data['password'])) {
            $data['password'] = md5($data['password']);
        }

        $xmlStr = Array2XML::createXML('SMS_API', $data)->saveXML();

        Log::info('call sms api: ' . $xmlStr);
        $response = Requests::post($this->apiUrl,
            array('Content-Type' => 'text/xml; charset=UTF-8'),
            $xmlStr,
            array('timeout' => 60)
        );
        Log::info('sms result: ' . $response->body);

        $result = XML2Array::createArray($response->body);

        return $result['response'];
    }
}
