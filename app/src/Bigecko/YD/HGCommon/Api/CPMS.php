<?php namespace Bigecko\YD\HGCommon\Api;

use Illuminate\Support\Facades\Log;
use Requests;

class CPMS
{
    public $apiUrl;

    public $params = array();

    public function __construct($apiUrl = null, array $params = array())
    {
        $this->apiUrl = $apiUrl;
        $this->params = $params;
    }

    public function call($command, array $data = array())
    {
        $data = array_merge($this->params, $data);
        $data['command'] = $command;
        $query = http_build_query($data);

        $fullurl = strpos($this->apiUrl, '?') === false
            ? $this->apiUrl . '?' . $query : $this->apiUrl . '&' . $query;

        Log::info('Call CPMS: ' . $fullurl, array(
            'ydapi' => true,
            'apiName' => 'CPMS',
            'apiData' => $data,
        ));
        $response = Requests::get($fullurl, array(), array(
            'timeout' => 25,
        ));
        Log::info('CPMS result: ' . $response->body, array(
            'ydapi' => true,
            'apiName' => 'CPMS',
            'apiData' => $data,
        ));

        $result = XML2Array::createArray($response->body);
        $result = reset($result);

        if ($result['Result'] != '00') {
            throw new CPMSErrorException($result['MSG'], $result['Result']);
        }

        return $result;
    }

}
