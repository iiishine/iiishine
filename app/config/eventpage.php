<?php

return array(

    // 活动code, 会用于生成微信服务号过水时的redirectcode
    'event_code' => 'ghb1610',

    // CLS API 地址
    'cls_api_url' => 'http://case.bigecko.com/ydapimock/index.php/api/cls',

    // CLS 参数配置
    'cls_params' => array(
        'VENDOR' => 'YD',
        'PWD' => 'UP',
    ),

    // 购开心官网API
    'gkx_api' => array(
        // 地址
        'url' => 'http://case.bigecko.com/ydapimock/index.php/api',

        // API相关参数
        'params' => array(
            'user' => 'webup',   // API用户名
            'pwd' => 'webup',    // API密码
        ),
    ),

    // 用于加密传给通用注册页面openid的密钥
    'ws_secretkey' => '67cf0b534f2107e90219f2ffa1dd9458',

    // 服务号过水url地址
    'wechat_auth_url' => 'http://case.bigecko.com/ydapimock/index.php/wechatauth',

    // 通用注册页面相关参数
    'commonreg' => array(
        // 通用注册页面地址
        'url' => 'http://case.bigecko.com/commonreg/index.php/m',

        // 通用注册页面密钥
        'key' => '123',

        // 传给通用注册页面的总公司id - 非卡友没有分享过
        'mgid' => 'lx15',

        // 传给通用注册页面的总公司id - 非卡友分享过
        'shared_mgid' => 'lx15share',
    ),

    // 通用后台相关
    'eventadmin' => array(
        // 活动后台地址（根路径）
        'base_url' => 'http://eventtest.goukaixincard.com/event/eventsite',

        // 固定参数
        'params' => array(
            'eventid' => 'ghb1610',
            'vendor' => 'gr',
            'password' => '123',
        ),
    ),

    // 微信相关
    'wechat' => array(

        // 微信公众号原始ID, 调用createUser， getUserinf接口时传入mgid参数用
        'originId' => '112233',

        'appId' => 'wxef918387079e62d1',                    // AppID(应用ID)
        'appSecret' => 'ad0a4dd41ce3a7955fb5c3a59485e942',  // AppSecret(应用密钥)
    ),

    // 语音验证码接口地址
    'voice_code_api' => 'http://localhost/out/happygo/taimacard/fake/voice',

    // 语音验证码接口参数
    'voice_code_params' => array(
        'AppCode'   => 'code', // 系统编码
        'account'   => 'user', // 账号
        'password'  => 'pass', // 密码
    ),

    //**** 短信API相关配置 *****

    /**
     * 默认短信发送平台
     */
    'default_sms_platform' => 'log',

    /**
     * 为每个短信平台设置对应的参数
     */
    'sms_platforms' => array(

        //默认短信平台设置
        'default' => array(
            // 短信API地址
            'sms_api_url' => 'http://localhost/out/happygo/eventpage1312/public/mock/sms',

            // 短信参数
            'sms_params' => array(
                'AppCode'   => 'code', // 系统编码
                'account'   => 'user', // 账号
                'password'  => 'pass', // 密码
            ),
        ),

        // log (开发测试用)
        'log' => array(
            'sms_api_url' => 'logfile',
            'sms_params' => array(),
        ),
    ),

    // 短信验证码文案，其中 {code} 为验证码占位符。
    'sms_verify_code_message' => '您正在申请购开心卡，手机验证码为：{code} , 请在页面上输入验证码并继续完成申办流程，谢谢！',

    // 证件类型
    'idTypes' => array(
        '1' => '身份证',
        '3' => '台胞证',
        '4' => '港澳通行证',
        '5' => '军官证',
        '2' => '护照',
    ),

    // RECORD_FRAUD_SMS api url 地址。
    'record_fraud_sms_api_url' => 'http://localhost/',

    // RECORD_FRAUD_SMS api 相关参数设置。
    'record_fraud_sms_params' => array(
        'AppCode' => 'GRF',
    ),

    // 活动所在城市
    'act_area' => '上海',
);
