<?php

namespace Bigecko\YD\HGCommon\Utils;

use Bigecko\YD\HGCommon\Api\CLS;
use Bigecko\YD\HGCommon\Exceptions\IDNoTailErrorException;
use Bigecko\YD\HGCommon\Exceptions\NoValidCardException;

class MemberCardFinder
{
    public $lastValidCard;

    /**
     * @var CLS
     */
    private $cls;

    public function __construct(CLS $cls)
    {
        $this->cls = $cls;
    }

    /**
     * 根据手机号码找卡友卡号
     *
     * @param string $mphone 手机号码
     * @return array
     * @throws IDNoTailErrorException
     * @throws NoValidCardException
     */
    public function findByPhone($mphone)
    {
        $members = $this->cls->memberLogin($mphone);

        return $members;
    }

}
