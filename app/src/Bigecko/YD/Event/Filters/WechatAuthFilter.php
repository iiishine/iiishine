<?php

namespace Bigecko\YD\Event\Filters;

use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Config\Repository;
use Illuminate\Routing\UrlGenerator;
use Bigecko\YD\Event\AuthManger;

class WechatAuthFilter
{

    /**
     * @var AuthManger
     */
    protected $auth;

    /**
     * @var Redirector
     */
    protected $redirector;

    /**
     * @var Repository
     */
    protected $config;
    /**
     * @var UrlGenerator
     */
    private $url;
    /**
     * @var Request
     */
    private $request;

    function __construct(AuthManger $auth,
                         Redirector $redirector,
                         Repository $config,
                         UrlGenerator $url,
                         Request $request)
    {
        $this->auth = $auth;
        $this->redirector = $redirector;
        $this->config = $config;
        $this->url = $url;
        $this->request = $request;
    }

    public function filter()
    {
        if ($this->auth->check() && !empty($this->auth->customer()->SER_OPENID)) {
            return;
        }

        $wechatAuthUrl = $this->config->get('eventpage.wechat_auth_url');
        $params = array(
            'rediretcode' => $this->config->get('eventpage.event_code'),
        );

        if (\App::environment() == 'dev') {
            $params['redirect_url'] = $this->url->to('/');
        }

        if ($this->request->has('rec_openid')) {
            $params['recommend_ser_openid'] = $this->request->get('rec_openid');
        }

        $wechatAuthUrl .= '?' . http_build_query($params) . '&' . $this->request->getQueryString();
        \Log::info('跳转到过水页面 ' . $wechatAuthUrl);
        return $this->redirector->guest($wechatAuthUrl);
    }
}
