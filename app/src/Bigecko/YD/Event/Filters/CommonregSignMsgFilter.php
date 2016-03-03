<?php

namespace Bigecko\YD\Event\Filters;

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Log\Writer;
use Illuminate\Support\Facades\Config;

class CommonregSignMsgFilter {


    /**
     * @var Application
     */
    private $app;
    /**
     * @var Request
     */
    private $request;
    /**
     * @var Writer
     */
    private $log;

    public function __construct(Request $request, Application $app, Writer $log)
    {
        $this->app = $app;
        $this->request = $request;
        $this->log = $log;
    }

    public function filter()
    {
        $params = array(
            'mgid' => $this->request->get('mgid'),
            'origAccount' => $this->request->get('origAccount'),
            'vAccount' => $this->request->get('vAccount'),
        );

        // 推荐人参数
        if ($this->request->has('recom')) {
            $params['recom'] = $this->request->get('recom');
        }

        $params['secretKey'] = Config::get('eventpage.commonreg.key');
        $encrypt = md5(http_build_query($params));

        if ($encrypt != $this->request->get('signMsg')) {
            $this->log->info('sign msg错误，应该是 ' . $encrypt);
            return 'sign msg 错误';
        }
    }

}
