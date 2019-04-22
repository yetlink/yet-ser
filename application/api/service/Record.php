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
     * @param  int     $start           开始时间
     * @param  int     $end             结束时间
     * @return mixed                    短地址
     * @throws
     */
    public function getLinkRecord($code, $start, $end)
    {
        $token = $this->getToken();
        $linkModel = new LinkModel();
        $link = $linkModel->where('token', '=' , $token)->where('code', '=' , $code)->with([
            'recordList' => function ($query) use ($start, $end) {
                return $query->where('create_time', '>' , stamp2date($start))
                    ->where('create_time', '<' , stamp2date($end));
            }
        ])->find();
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
        foreach ($list as $k => $item)
        {
            if (isset($browser[$item['browser']])) {
                $browser[$item['browser']]['count']++;
            }
            if (isset($os[$item['os']])) {
                $os[$item['os']]['count']++;
            }
            $v = date('Y-m-d', date2stamp($item['create_time']));
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