<?php

namespace Bigecko\YD\HGCommon\Utils;

use Bigecko\YD\HGCommon\Api\CheckMPhone;
use Bigecko\YD\HGCommon\Models\MPhoneCheckResult;

class MPhoneCleaner
{
    /**
     * @var CheckMPhone
     */
    protected $checker;

    /**
     * @param CheckMPhone $checker
     */
    public function __construct(CheckMPhone $checker)
    {
        $this->checker = $checker;
    }

    public function clean($mphone)
    {
        $resultModel = MPhoneCheckResult::where('MPHONE', $mphone)->first();
        if ($resultModel && $resultModel->RESULT != '2') {
            return $resultModel->RESULT;
        }

        // 清洗手机号码
        $checkResult = $this->checker->call(null, array(
            'phone' => $mphone,
            'optype' => '0',
        ));
        $checkResultCode = $checkResult['phone_list']['check_result'];

        // 记录清洗手机号码结果
        if ($resultModel) {
            $resultModel->RESULT = $checkResultCode;
        }
        else {
            $resultModel = new MPhoneCheckResult(array(
                'MPHONE' => $mphone,
                'RESULT' => $checkResultCode,
            ));
        }

        $resultModel->save();

        return $checkResultCode;
    }

}
