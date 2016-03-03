<?php namespace Bigecko\YD\HGCommon\Utils;

use Bigecko\YD\HGCommon\Api\VoiceCode;


class SmsVerifier
{
    protected $sms;
    protected $session;

    // Session key prefix.
    public $prefix = 'sms_verify_code_';

    public $smsTpl = '{code}';

    public function __construct(VoiceCode $sms, $session)
    {
        $this->sms = $sms;
        $this->session = $session;
    }

    /**
     * 发送短信验证码
     *
     * @param $mobile
     *   手机号码
     *
     * @param $tpl
     *   短信文案模板
     *
     * @return array
     */
    public function sendCode($mobile, $tpl = null)
    {
        $message = $this->generateCodeMsg($tpl);
        $code = $message['code'];

        $sessKey = $this->prefix . $mobile;
        $this->session->put($sessKey, $code);

        $result = $this->sms->call(null, array(
            'Phone' => $mobile,
            'VerifyCode' => $code,
        ));

        return array(
            'code' => $message,
            'smsResult' => $result,
        );
    }

    /**
     * @param null $tpl
     * @return array
     */
    public function generateCodeMsg($tpl = null)
    {
        $code = (string)rand(1000, 9999);

        if (is_null($tpl)) {
            $tpl = $this->smsTpl;
        }
        $msg = str_replace('{code}', $code, $tpl);

        return array(
            'code' => $code,
            'msg' => $code,
        );
    }

    public function forgetPhone($mobile)
    {
        $sessKey = $this->prefix . $mobile;
        $this->session->forget($sessKey);
    }

    public function hasCode($mobile)
    {
        if (empty($mobile)) {
            return false;
        }

        $sessKey = $this->prefix . $mobile;
        return $this->session->has($sessKey);
    }

    public function checkCode($mobile, $code)
    {
        if (empty($mobile)) {
            return false;
        }
        $sessKey = $this->prefix . $mobile;
        return $this->session->get($sessKey) == $code;
    }
}
