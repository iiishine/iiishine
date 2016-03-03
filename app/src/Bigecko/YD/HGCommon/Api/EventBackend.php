<?php
namespace Bigecko\YD\HGCommon\Api;

use Requests;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class EventBackend extends YDApi
{
    public $apiName = 'eventadmin';

    public $method = 'post';

    /**
     * @param       $key
     * @param       $area
     * @param array $vars
     *
     * @return string
     */
    public function text($key, $area = '上海', array $vars = null)
    {
        $cacheKey = "eventtext.$area.$key";

        $self = $this;

        $text = Cache::remember($cacheKey, 10, function() use ($self, $key, $area) {
            $result = $self->get("text/$key", array('area' => $area));

            $content = $result->value ? nl2br($result->value) : $key;

            return $content;
        });

        if (!empty($vars)) {
            $text = str_replace(array_keys($vars), array_values($vars), $text);
        }

        return $text;
    }

    public function call($command = null, array $data = array())
    {
        $data = array_merge($this->params, $data);
        $url = rtrim($this->apiUrl, '/') . '/' . $command;

        if ($this->method == 'get') {
            $url .= '?' . http_build_query($data);
            Log::info('call ' . $this->apiName . ": $url");
            $response = Requests::get($url, array(), array(
                'timeout' => 60,
            ));
        }
        else {
            Log::info('call ' . $this->apiName . ": $url , " . json_encode($data));
            $response = Requests::post($url, array(), $data, array(
                'timeout' => 25,
            ));
        }


        Log::info($this->apiName . ' result: ' . $response->body);

        return json_decode($response->body);
    }


}
