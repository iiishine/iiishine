<?php

namespace Bigecko\YD\HGCommon\Api;

use Requests;
use Illuminate\Support\Facades\Log;

class CheckMPhone extends YDApi
{
    public $apiName = 'check mphone';

    public $method = 'post';

    protected function postRequest($data)
    {
        Log::info('Call ' . $this->apiName . ' : ' . $this->apiUrl . ' 参数: ' . json_encode($data), array(
            'ydapi' => true,
            'apiName' => $this->apiName,
            'apiData' => $data,
        ));
        $response = Requests::post($this->apiUrl, array(), $data, array(
            'timeout' => 23,
        ));

        return $response;
    }
}
