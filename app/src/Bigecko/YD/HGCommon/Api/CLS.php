<?php namespace Bigecko\YD\HGCommon\Api;

class CLS extends YDApi
{
    public $apiName = 'CLS';

    public $method = 'get';

    public function selectMemberAll(array $data)
    {
        $result = $this->call('SEL_MEMBER_ALL', $data);
        $memberList = $result['MEMBER_LIST'];
        return isset($memberList['MEMBER_ID']) ? array($memberList) : $memberList;
    }

    public function selectMemberExist(array $data)
    {
        $result = $this->call('SEL_MEMBER_EXIST', $data);
        $memberList = $result['MEMBER_LIST'];
        return isset($memberList['MEMBER_ID']) ? array($memberList) : $memberList;
    }

    public function seleMemberAllCard(array $data)
    {
        $result = $this->call('SEL_MEMBER_ALL_CARD', $data);
        $list = $result['LOYALTY_LIST'];
        return isset($list['LOYALTY_ID']) ? array($list) : $list;
    }

    public function selRealPoint($memberId, $attr = null) {
        $pointData = $this->call('SEL_REAL_POINT', array(
            'MEMBER_ID' => $memberId,
        ));

        if (!is_null($attr)) {
            return (int)$pointData[$attr];
        }

        return $pointData;
    }

    /**
     * MEMBER_LOGIN会员登录验证取得虚拟账号
     *
     * @param $mphone
     *
     * @return mixed
     */
    public function memberLogin($mphone)
    {
        $result = $this->call('MEMBER_LOGIN', array(
            'Mphone' => $mphone,
        ));

        return $result;
    }

    public function call($command = null, array $data = array())
    {
        $result = parent::call($command, $data);

        $code = $result['RESPONSE']['CODE'];
        if ($code != 'CLM_OK') {
            throw new CLSErrorException($code);
        }

        return $result['DATA'];
    }
}
