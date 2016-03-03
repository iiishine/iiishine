<?php

namespace Bigecko\YD\HGCommon\Api;

class UrlShorter extends YDApi
{

    public $apiName = 'URL Short';

    public $method = 'get';

    public function generate($url)
    {
        $result = $this->call('GENERATE_SHORT_URL', array(
            'URL' => urlencode($url),
        ));

        $code = $result['RESPONSE']['CODE'];
        if ($code != 'CLM_OK') {
            throw new CLSErrorException($code);
        }

        return (string)$result['SHORT_URL'];
    }

}
