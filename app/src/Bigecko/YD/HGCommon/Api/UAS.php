<?php namespace Bigecko\YD\HGCommon\Api;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class UAS
{
    protected $apiUrl;
    protected $appCode;

    public function __construct($apiUrl = null, $appCode = null)
    {
        if ($apiUrl) {
            $this->setApiUrl($apiUrl);
        }

        if ($appCode) {
            $this->setAppCode($appCode);
        }
    }

    public function setApiUrl($url)
    {
        $this->apiUrl = $url;
    }

    public function getApiUrl()
    {
        return $this->apiUrl;
    }

    public function setAppCode($code)
    {
        $this->appCode = $code;
    }

    public function getAppCode()
    {
        return $this->appCode;
    }

    public function login($account, $password)
    {
        $result = $this->call('Login', array(
            'Account' => $account,
            'Password' => md5($password),
        ));

        if ($result['Result'] != '00') {
            throw new UASLoginErrorException();
        }

        return $result;
    }

    public function call($command, array $data = array())
    {
        $data['command'] = $command;
        if (!isset($data['AppCode'])) {
            $data['AppCode'] = $this->appCode;
        }

        $xml = Array2XML::createXML('UAS_API', $data)->saveXML();
        Log::info('Call uas ' . $xml);
        $headers = array('Content-Type' => 'text/xml; charset=UTF-8');
        $response = \Requests::post($this->apiUrl, $headers, $xml, array(
            'timeout' => 25,
        ));
        Log::info('Uas response: '. $response->body);

        $result = XML2Array::createArray($response->body);

        return $result['UAS_API'];
    }

    public function checkPermission($permissionCode)
    {
        // TODO Set uas_info from setter or __construct.
        $uas_info = Session::get('uas_info');
        if (!$uas_info) {
            return false;
        }
        return isset($uas_info->permissions[$permissionCode]);
    }
}
