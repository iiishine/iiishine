<?php namespace Bigecko\YD\HGCommon\Api;

use Illuminate\Support\Facades\Log;
use Requests;

class EMS
{
    public $apiUrl;

    public $params = array();

    public function __construct($apiUrl = null, array $params = array())
    {
        $this->apiUrl = $apiUrl;
        $this->params = $params;
    }

    public function getJoinEvent(array $data)
    {
        $data['Date'] = date('Ymd');
        $data['Time'] = date('His');

        return $this->call('GET_JOIN_EVENT', $data);
    }

    public function call($command, array $data = array())
    {
        $data = array_merge($this->params, $data);
        $data['command'] = $command;
        $query = http_build_query($data);

        $fullurl = strpos($this->apiUrl, '?') === false
            ? $this->apiUrl . '?' . $query : $this->apiUrl . '&' . $query;

        Log::info('Call EMS: ' . $fullurl);
        $response = Requests::get($fullurl);
        Log::info('EMS result: ' . $response->body);

        $result = XML2Array::createArray($response->body);
        $result = reset($result);

        if ($result['RESPONSE']['CODE'] != '00') {
            throw new EMSErrorException($result['MSG'], $result['Result']);
        }

        return $result;
    }

}
