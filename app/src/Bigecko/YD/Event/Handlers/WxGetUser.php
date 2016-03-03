<?php
namespace Bigecko\YD\Event\Handlers;

use Bigecko\YD\Event\AuthManger;
use Bigecko\YD\HGCommon\Api\CLSErrorException;
use Bigecko\YD\HGCommon\Exceptions\NoValidCardException;
use Bigecko\YD\HGCommon\Utils\MemberCardFinder;
use Carbon\Carbon;

class WxGetUser
{
    /**
     * @var AuthManger
     */
    private $authManger;

    /**
     * @var MemberCardFinder
     */
    private $memberCardFinder;

    public function __construct(AuthManger $authManger, MemberCardFinder $memberCardFinder)
    {
        $this->authManger = $authManger;
        $this->memberCardFinder = $memberCardFinder;
    }

    public function handle($wxUser, $updateUserTime = false)
    {
        if ($wxUser->status_code != '0') {
            return;
        }

        if (!$this->authManger->check()) {
            return;
        }

        $customer = $this->authManger->customer();

        // 更新用户类型相关字段
        if ($customer->USER_TYPE != $wxUser->usertype) {
            $customer->USER_TYPE = $wxUser->usertype;

            // 更新成为微信用户/微信卡友的时间
            if ($updateUserTime) {
                if (!empty($wxUser->time_to_user_time)) {      // 微信用户
                    $customer->TIME_TO_USER = $wxUser->time_to_user_time;
                }

                if (!empty($wxUser->time_to_member_time)) { // 微信卡友
                    $customer->TIME_TO_MEMBER = $wxUser->time_to_member_time;
                }
            }
        }

        // 是否关注微信
        $customer->SUBSCRIBE_STATUS = $wxUser->subscribe_status;

        if (empty($customer->MPHONE) && !empty($wxUser->mobile)) {
            $customer->MPHONE = $wxUser->mobile;
        }

        if (empty($customer->MEMBER_ID) && !empty($wxUser->memberid)) {
            $customer->MEMBER_ID = $wxUser->memberid;
        }

        // 卡友相关信息
        if (!empty($customer->MPHONE)) {
            try {
                $member = $this->memberCardFinder->findByPhone($customer->MPHONE);

                if (empty($customer->MEMBER_ID)) {
                    $customer->MEMBER_ID = $member['MEMBER_ID'];
                }
            }
            catch (NoValidCardException $e) { // 卡友，无有效卡
                $customer->VALIDCARD = false;
                $customer->save();
                \App::abort(200, '对不起，该卡号已被停用请选择其他卡继续参加活动');
            }
            catch (CLSErrorException $e) { // 非卡友
                //TODO 判断是否是未找到记录的cls返回代码
            }
        }

        if ($customer->isMember()) {
            $customer->VALIDCARD = true;
        }

        $customer->save();
    }
}
