<?php
namespace Bigecko\YD\HGCommon\Api;

use Log;
use Requests;
use Intervention\Image\ImageManagerStatic as Image;

class Eventup
{
    protected $eventupUrl;

    protected $eventupKey;

    protected $eventId;

    public function __construct($eventupUrl, $eventupKey, $eventId = null)
    {
        $this->eventupUrl = $eventupUrl;
        $this->eventupKey = $eventupKey;
        $this->eventId = $eventId;
    }

    public function setEventId($eventId)
    {
        $this->eventId = $eventId;
    }

    public function put($filepath)
    {
        $params = array(
            'imgext' => 'jpg',
            'ts' => time(),
            'eventid' => $this->eventId,
            'type' => 'data',
        );
        $params['sign'] = md5(http_build_query($params) . $this->eventupKey);

        $upUrl = $this->eventupUrl . '/index.php/img?' . http_build_query($params);

        $imgData = Image::make($filepath)->encode('jpg')->encode('data-url');

        Log::info('call eventup: ' . $upUrl);
        $response = Requests::post($upUrl, array(), array(
            '_data' => (string) $imgData
        ));
        Log::info('eventup result: ' . $response->body);

        return json_decode($response->body);
    }

    public function fullurl($filename)
    {
        return $this->eventupUrl . '/public/uploads/' . $this->eventId . '/' . $filename;
    }
}
