<?php namespace Bigecko\YD\HGCommon\Api;

use Illuminate\Support\Facades\Log;

use Requests;

class UoleemSms extends SMS implements SMSInterface
{
    public function send($phoneNumber, $message)
    {
        $data = $this->params;
        $data['mobile'] = $phoneNumber;
        $data['content'] = $message;

        $query = http_build_query($data);

        $fullurl = strpos($this->apiUrl, '?') === false
            ? $this->apiUrl . '?' . $query : $this->apiUrl . '&' . $query;

        Log::info('Call sms api: ' . $fullurl);
        $response = Requests::get($fullurl);
        Log::info('Sms result: ' . $response->body);

        return strpos($response->body, '0，') === 0;
    }
}
