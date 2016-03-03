<?php
namespace Bigecko\YD\HGCommon\Api;

use Illuminate\Support\Facades\Log;

class RecordFraudSms extends YDApi
{
    public $apiName = 'RECORD_FRAUD_SMS';

    public $method = 'get';

    public function call($command = null, array $data = array())
    {
        $data = array_merge($this->params, $data);

        $response = $this->getRequest($data);

        Log::info($this->apiName . ' result: ' . $response->body,  array(
            'ydapi' => true,
            'apiName' => $this->apiName,
            'apiData' => $data,
        ));

        return $response->body;
    }
}
