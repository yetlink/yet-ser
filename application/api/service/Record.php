<?php
/**
 * Created by PhpStorm.
 * User: Brandon
 */

namespace app\api\service;

use app\api\model\Record as RecordModel;
use app\api\model\Link as LinkModel;
use app\api\util\Util;
use think\Request;

class Record extends BaseService
{
    /**
     * 短地址访问记录
     * @access public
     * @param  string   $code        短地址
     * @throws
     */
    public static function record($code)
    {
        $rm = new RecordModel();
        $util = new Util();
        $rm->data([
            'code' => $code,
            'agent' => $util->getAgent(),
            'lang' => $util->getLang(),
            'browser' => $util->getBrowser(),
            'os' => $util->getOS(),
            'ip' => Request::instance()->ip()
        ]);
        $rm->save();
    }

    /**
     * 获取短地址统计数据
     * @access public
     * @param  string     $code         短地址
     * @return mixed                    短地址
     * @throws
     */
    public function getLinkRecord($code)
    {
        $token = $this->getToken();
        $linkModel = new LinkModel();
        $link = $linkModel->where('token', '=' , $token)->where('code', '=' , $code)->with('recordList')->find();
        return $this->recordCount($link['recordList']);
    }

    /**
     * 获取短地址统计数据
     * @access public
     * @param  array     $list         短地址
     * @return mixed                    短地址
     * @throws
     */
    private function recordCount($list)
    {
        $util = new Util();
        $browser = $util->getBrowserItems();
        $os = $util->getOSItems();
        $pv = count($list); // 次数
        $uv = []; // 一天次数
        $ip = []; // 用户
        foreach ($list as $item)
        {
            if (isset($browser[$item['browser']])) {
                $browser[$item['browser']]['count']++;
            }
            if (isset($os[$item['os']])) {
                $browser[$item['os']]['count']++;
            }
            $v = date('Y-m-d',strtotime($item['create_time']));
            $uv[$item['ip'].'_'.$v] = true;
            $ip[$item['ip']] = true;

        }
        $visit = [
            [
                'text' =>   'pv',
                'count' =>  $pv
            ],
            [
                'text' =>   'uv',
                'count' =>  count($uv)
            ],
            [
                'text' =>   'ip',
                'count' =>  count($ip)
            ]
        ];
        return [
            'browser' => array_values($browser),
            'os' => array_values($os),
            'visit' => $visit
        ];
    }
}