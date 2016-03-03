<?php  namespace Bigecko\YD\HGCommon\Api;

use Illuminate\Support\Facades\Log;
use Requests;

/**
 * Class YDApi
 * @package Bigecko\YD\HGCommon\Api
 *
 * TODO 缓存调用结果
 */
abstract class YDApi
{
    public $apiName;

    public $apiUrl;

    public $params = array();

    public $method = 'get';

    public $xmlRoot = 'API';

    public function __construct($apiUrl = null, array $params = array())
    {
        $this->apiUrl = $apiUrl;
        $this->params = $params;
    }

    public function get($commnad = null, array $data = array())
    {
        $oldMethod = $this->method;
        $this->method = 'get';
        $result = $this->call($commnad, $data);
        $this->method = $oldMethod;
        return $result;
    }

    public function post($commnad = null, array $data = array())
    {
        $oldMethod = $this->method;
        $this->method = 'post';
        $result = $this->call($commnad, $data);
        $this->method = $oldMethod;
        return $result;
    }

    public function call($command = null, array $data = array())
    {
        $data = array_merge($this->params, $data);

        if (!is_null($command)) {
            $data['command'] = $command;
        }

        if ($this->method == 'get') {
            $response = $this->getRequest($data);
        }
        else {
            $response = $this->postRequest($data);
        }

        Log::info($this->apiName . ' result: ' . $response->body,  $data);

        $result = XML2Array::createArray($response->body);
        return reset($result);
    }

    protected function getRequest($data)
    {
        $query = http_build_query($data);

        $fullurl = strpos($this->apiUrl, '?') === false
            ? $this->apiUrl . '?' . $query : $this->apiUrl . '&' . $query;

        Log::info('Call ' . $this->apiName . ' : ' . $fullurl, array(
            'ydapi' => true,
            'apiName' => $this->apiName,
            'apiData' => $data,
        ));
        $response = Requests::get($fullurl, array(), array(
            'timeout' => 25,
        ));

        return $response;
    }

    protected function postRequest($data)
    {
        $xml = Array2XML::createXML($this->xmlRoot, $data)->saveXML();
        Log::info('Call ' . $this->apiName . ' : ' . $xml, array(
            'ydapi' => true,
            'apiName' => $this->apiName,
            'apiData' => $data,
        ));
        $headers = array('Content-Type' => 'text/xml; charset=UTF-8');
        $response = Requests::post($this->apiUrl, $headers, $xml, array(
            'timeout' => 25,
        ));

        return $response;
    }
}
