<?php
namespace Bigecko\YD\HGCommon\Api;

use Illuminate\Support\Facades\Log;
use Requests;

/**
 * 购开心官网API
 * @package Bigecko\YD\HGCommon\Api
 */
class Gkx extends YDApi
{
    public $apiName = 'gkx webapi';

    public $method = 'post';

    public $wechatOriginId = null;

    public function call($command = null, array $data = array())
    {
        $data = array_merge($this->params, $data);

        if (in_array($command, array(
            'weixin/createUser',
            'weixin/getUserinfo',
        ))) {
            $data['mgid'] = $this->wechatOriginId;
        }

        $url = trim($this->apiUrl, '/') . '/' . $command;

        Log::info('call ' . $this->apiName . ": $url " , $data);
        $response = Requests::post($url, array(), $data, array(
            'timeout' => 25,
        ));
        Log::info($this->apiName . ' result: ' . $response->body);

        return json_decode($response->body);
    }


}
