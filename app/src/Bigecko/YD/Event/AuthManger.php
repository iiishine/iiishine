<?php

namespace Bigecko\YD\Event;

use Illuminate\Session\Store as SessionStore;
use Illuminate\Events\Dispatcher as EventDispatcher;
use Bigecko\YD\Qinzi\Customer;

class AuthManger
{

    /**
     * @var SessionStore
     */
    protected $session;

    protected $sessionKey = 'yd.customer_id';

    protected $customer = null;

    /**
     * @var EventDispatcher
     */
    protected $events;

    public function __construct(SessionStore $session, EventDispatcher $events)
    {
        $this->session = $session;
        $this->events = $events;
    }

    public function check()
    {
        return !is_null($this->customer());
    }

    /**
     * 获取当前用户对象
     *
     * @return Customer|null
     */
    public function customer()
    {
        if (!is_null($this->customer)) {
            return $this->customer;
        }

        $id = $this->session->get($this->sessionKey);
        if (!$id) {
            return null;
        }

        $this->customer = Customer::find($id);
        return $this->customer;
    }

    /**
     * 通过手机号码查询用户
     *
     * @param      $phone
     * @param null $creation
     *
     * @return Customer|null
     */
    public function findByPhone($phone, $creation = null)
    {
        $customer = Customer::where('MPHONE', $phone)->first();

        if (!$customer && !is_null($creation)) {
            $customer = $this->createCustomer($creation);
        }

        return $customer;
    }

    /**
     * 通过微信openid查询用户
     *
     * @param      $openid
     * @param null $creation
     *
     * @return Customer|null
     */
    public function findByOpenid($openid, $creation = null)
    {
        $customer = Customer::where('WX_OPENID', $openid)->first();

        if (!$customer && !is_null($creation)) {
            $customer = $this->createCustomer($creation);
        }

        return $customer;
    }

    /**
     * @param      $serOpenid
     * @param null $creation
     *
     * @return Customer|static
     */
    public function findBySerOpenid($serOpenid, $creation = null)
    {
        $customer = Customer::where('SER_OPENID', $serOpenid)->first();

        if (!$customer && !is_null($creation)) {
            $customer = $this->createCustomer($creation);
        }

        return $customer;
    }

    /**
     * 新建用户
     *
     * @param array $creation
     *
     * @return \Illuminate\Database\Eloquent\Model|static
     */
    public function createCustomer(array $creation)
    {
        $customer = Customer::create($creation);

        return Customer::find($customer->ID);
    }

    /**
     * 通过手机号码登录用户
     *
     * @param      $phone
     * @param null $creation
     *
     * @return Customer|bool
     */
    public function logPhoneIn($phone, $creation = null)
    {
        $customer = $this->findByPhone($phone, $creation);

        if (!$customer) {
            return false;
        }

        return $this->login($customer);
    }

    /**
     * 通过微信openid登录
     *
     * @param      $serSpenid
     * @param null $creation
     *
     * @return Customer|bool
     */
    public function logSerOpenidIn($serSpenid, $creation = null)
    {
        $customer = $this->findBySerOpenid($serSpenid, $creation);

        if (!$customer) {
            return false;
        }

        return $this->login($customer);
    }

    /**
     * 登录用户
     *
     * @param Customer $customer
     *
     * @return Customer
     */
    public function login(Customer $customer)
    {
        $this->session->put($this->sessionKey, $customer->getKey());
        $this->customer = $customer;

        $this->events->fire('gkx.auth.login', array($customer));

        return $customer;
    }

    /**
     * 注销用户
     */
    public function logout()
    {
        $this->session->forget($this->sessionKey);
        $this->events->fire('gkx.auth.logout', array($this->customer()));
        $this->customer = null;
    }
}
