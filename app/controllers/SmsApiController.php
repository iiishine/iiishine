<?php

use Bigecko\YD\HGCommon\Api\RecordFraudSms;
use Bigecko\YD\HGCommon\Api\EventBackend;
use Carbon\Carbon;

class SmsApiController extends Controller
{

    /**
     * @var RecordFraudSms
     */
    protected $recordFraudSms;
    /**
     * @var EventBackend
     */
    protected $eventBackend;

    public function __construct(RecordFraudSms $recordFraudSms, EventBackend $eventBackend)
    {
        $this->recordFraudSms = $recordFraudSms;
        $this->eventBackend = $eventBackend;
    }

    public function postSendVerifierCode()
    {
        $phoneNumber = Input::get('phone');
        if (!preg_match("/^1\d{10}$/", $phoneNumber)) {
            return Response::json(array(
                'code' => 400,
                'status' => '400 Bad Request',
                'msg' => '手机号码错误',
            ));
        }

        $captcha = str_replace(' ', '',Input::get('captcha'));
        if (!Captcha::check($captcha)) {
            return Response::json(array(
                'code' => 400,
                'status' => '400 Bad Request',
                'msg' => '图形验证码错误',
            ));
        }

        $cacheKey = 'sms_blocking_' . $phoneNumber;
        if (Cache::has($cacheKey) && !Config::get('app.debug')) {
            return Response::json(array(
                'msg' => '验证码获取频繁，请一分钟后再试',
                'code' => 200,
            ));
        }
        else {
            Cache::put($cacheKey, true, 1);
        }

        $verifier = App::make('smsVerifier');
        $smslimit = $this->eventBackend->get('smscodelimit');

        try {
            $tpl = null;

            $canSend = true;

            // 检查是否达到每天发送短信的限制
            $start = Carbon::createFromFormat('H:i:s', '00:00:00');
            $end = Carbon::createFromFormat('H:i:s', '23:59:59');
            $daySent = SMSLog::getNumSent($phoneNumber,
                $start->format('Y-m-d H:i:s'),
                $end->format('Y-m-d H:i:s')
            );
            if ($daySent >= $smslimit->daily) {
                $canSend = false;
            }

            // 检查是否达到每小时发送短信的限制
            if ($canSend) {
                $start = Carbon::now()->minute(0)->second(0);
                $end = clone $start;
                $end->addHour();
                $hourSent = SMSLog::getNumSent($phoneNumber,
                    $start->format('Y-m-d H:i:s'),
                    $end->format('Y-m-d H:i:s')
                );

                if ($hourSent >= $smslimit->hourly) {
                    $canSend = false;
                }
            }

            if ($canSend) {
                $result = $verifier->sendCode($phoneNumber, $tpl);
                $message = $result['code'];
                $smsResult = $result['smsResult'];
            }
            else {
                Log::info("$phoneNumber 短信发送频率超出限制，调用recordFraudSms记录");
                // 短信发送频率超出限制，调用recordFraudSms记录
                $message = $verifier->generateCodeMsg($tpl);
                $repCount = SMSLog::getNumByMobile($phoneNumber,
                    $start->format('Y-m-d H:i:s'),
                    $end->format('Y-m-d H:i:s')
                );
                $this->recordFraudSms->call(null, array(
                    'Phone' => $phoneNumber,
                    'Message' => '验证码请求次数超限',
                    'SendDate' => date('Y-m-d'),
                    'StartTime' => $start->format('H:i'),
                    'EndTime' => $end->format('H:i'),
                    'RepCount' => $repCount,
                ));

                return Response::json(array(
                    'msg' => '验证码获取频繁，请联系购开心客服 4000218826',
                    'code' => 200,
                ));
            }

            SMSLog::create(array(
                'MOBILE' => $phoneNumber,
                'CLIENT_IP' => Input::getClientIp(),
                'CONTENT' => $message['msg'],
                'VERIFY_CODE' => $message['code'],
                'SENT' => $canSend,
            ));

            if ($smsResult['error'] != '0') {
                $smsResult['message'] = '手机号码格式不正确';
            }

            return Response::json(array(
                'code' => $smsResult['error'] == '0' ? 200 : 400,
                'status' => 'OK',
                'sent' => $canSend,
                'smsmsg' => $smsResult['message'],
            ));
        }
        catch (Exception $e) {
            Log::error($e);
            return Response::json(array(
                'code' => 500,
                'status' => 'Server error',
            ));
        }
    }

    public function postCheckVerifierCode()
    {
        $verifier = App::make('smsVerifier');

        return Response::json(array(
            'code' => 200,
            'result' => $verifier->checkCode(
                Input::get('mobile'), trim(Input::get('code'))),
        ));
    }

}
