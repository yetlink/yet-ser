<?php

namespace app\api\util;

class Util
{

    public $agent = null;

    protected $Browser = [
        [
            'key' => 'MicroMessenger',  // 关键字
            'text' => 'WeiXin',         // 值
            'value' => 1,               // 枚举
        ],
        [
            'key' => ' QQ/',
            'text' => 'QQ',
            'value' => 2,
        ],
        [
            'key' => 'QQBrowser',
            'text' => 'QQBrowser',
            'value' => 3,
        ],
        [
            'key' => 'UCBrowser',
            'text' => 'UC',
            'value' => 4,
        ],
        [
            'key' => 'Trident',
            'text' => 'IE',
            'value' => 10,
        ],
        [
            'key' => 'MSIE',
            'text' => 'IE',
            'value' => 10,
        ],
        [
            'key' => 'Firefox',
            'text' => 'Firefox',
            'value' => 15,
        ],
        [
            'key' => 'Opera',
            'text' => 'Opera',
            'value' => 20,
        ],
        [
            'key' => 'Chrome',
            'text' => 'Chrome',
            'value' => 25,
        ],
        [
            'key' => 'Safari',
            'text' => 'Safari',
            'value' => 30,
        ],
        [
            'key' => '/',
            'text' => 'Other',
            'value' => 50,
        ],
    ];

    protected $OS = [
        [
            'key' => 'iPhone',          // 关键字
            'text' => 'iPhone',         // 值
            'value' => 1,               // 枚举
        ],
        [
            'key' => 'iPad',
            'text' => 'iPad',
            'value' => 2,
        ],
        [
            'key' => 'ndroid',
            'text' => 'Android',
            'value' => 3,
        ],
        [
            'key' => 'Windows NT',
            'text' => 'Windows',
            'value' => 10,
        ],
        [
            'key' => 'mac',
            'text' => 'MAC',
            'value' => 20,
        ],
        [
            'key' => 'Mac',
            'text' => 'MAC',
            'value' => 20,
        ],
        [
            'key' => '/',
            'text' => 'Other',
            'value' => 50,
        ],
    ];

    protected $lang = [
        [
            'key' => '/zh-c/i',          // 关键字
            'text' => '简体中文',         // 值
            'value' => 1,               // 枚举
        ],
        [
            'key' => '/zh/i',
            'text' => '繁體中文',
            'value' => 2,
        ],
        [
            'key' => '/en/i',
            'text' => 'English',
            'value' => 3,
        ]
    ];

    public function __construct()
    {
        $this->agent = $_SERVER['HTTP_USER_AGENT'];
    }


    /**
     * 获取浏览器UA
     * @access      public
     * @return      string
     * @throws
     */
    public function getAgent()
    {
        return $this->agent;
    }


    /**
     * 获取浏览器
     * @access      public
     * @return      int         浏览器枚举
     * @throws
     */
    public function getBrowser()
    {
        $agent = $this->agent;
        foreach ($this->Browser as $v)
        {
            if (strpos($agent, $v['key'])) {
                return $v['value'];
            }
        }
        return 50;
    }


    /**
     * 获取浏览器语言
     * @access      public
     * @return      string        语言
     * @throws
     */
    public function getLang()
    {
        if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
            $Lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 4);
            foreach ($this->lang as $v)
            {
                if (preg_match($v['key'],$Lang)) {
                    return $v['value'];
                }
            }
            return 3;
        }
        return 0;
    }


    /**
     * 获取浏览器环境
     * @access      public
     * @return      int        系统枚举
     * @throws
     */
    public function getOS()
    {
        $agent = $this->agent;
        foreach ($this->OS as $v)
        {
            if (strpos($agent, $v['key'])) {
                return $v['value'];
            }
        }
        return 50;
    }

    /**
     * 获取浏览器列表
     */
    public function getBrowserItems()
    {
        return $this->getItems($this->Browser);
    }

    /**
     * 获取系统列表
     */
    public function getOSItems()
    {
        return $this->getItems($this->OS);
    }

    protected function getItems($list)
    {
        $items = [];
        forEach($list as $value){
            $items[(int)$value['value']] = [
                'text' => $value['text'],
                'count' => 0
            ];
        }
        return $items;
    }
}