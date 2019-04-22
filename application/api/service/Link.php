<?php
/**
 * Created by PhpStorm.
 * User: Brandon
 */

namespace app\api\service;


use app\api\model\Link as LinkModel;
use app\lib\exception\MissException;

class Link extends BaseService
{
    /**
     * 短地址重定向
     * @access public
     * @param  string   $code        短地址
     * @return   mixed   重定向地址
     * @throws
     */
    public function jump($code)
    {
        $linkModel = new LinkModel();
        $link = $linkModel->where('code', '=' , $code)
            ->where('expire_time', '>' , date('Y-m-d H:i:s', time()))->find();
        if (!$link) {
            return redirect('/404',404);
        }
        if ($link['password']) {
            return redirect('/401/'.$code,401);
        }
        Record::record($code);
        return redirect($link['url'],302);
    }

    /**
     * 新建短地址
     * @access public
     * @param  string   $url        地址
     * @param  int      $expire     有效期
     * @param  string   $password   密码
     * @return mixed                短地址
     * @throws
     */
    public function createLink($url, $expire, $password)
    {
        $token = $this->getToken();
        $start = date('y-m-d') . ' 00:00:00';
        $end = date('y-m-d') . ' 23:59:59';
        $linkModel = new LinkModel();
        $link = $linkModel->where('url', '=', $url)->where('token', '=', $token)
            ->where('password', '=', $password)->where('create_time', '>', $start)
            ->where('create_time', '<', $end)->find();
        if (!$link) {
            $link = $this->saveLink($url, $expire, $password, $token);
        }
        return $link;
    }

    /**
     * 修改短地址
     * @access public
     * @param  int      $id         id
     * @param  int      $expire     有效期
     * @param  string   $password   密码
     * @return mixed                短地址
     * @throws
     */
    public function modifyLink($id, $expire, $password)
    {
        $token = $this->getToken();
        $linkModel = new LinkModel();
        $link = $linkModel->where('token', '=', $token)->where('id', '=', $id)->find();
        if (!$link) {
            throw new MissException(['msg' => '短地址未找到!']);
        }
        $link->password = $password;
        $link->expire_time = date('Y-m-d H:i:s', $expire);
        $link->save();
        return $link;
    }

    /**
     * 获取无密码短地址  code
     * @access public
     * @param  string   $code           地址
     * @return mixed                    短地址
     * @throws
     */
    public function getByCode($code)
    {
        $linkModel = new LinkModel();
        $link = $linkModel->where('code', '=' , $code)->find();
        if (!$link) {
            throw new MissException(['msg' => '短地址不存在!']);
        }
        if ($link['password']) {
            throw new MissException(['msg' => '短地址已加密!']);
        }
        if (date2stamp($link['expire_time']) < time()) {
            throw new MissException(['msg' => '短地址已过期!']);
        }
        return $link;
    }

    /**
     * 获取加密短地址  密码
     * @access public
     * @param  string   $code           地址
     * @param  string   $password       密码
     * @return mixed                    短地址
     * @throws
     */
    public function getByPassword($code, $password)
    {
        $linkModel = new LinkModel();
        $link = $linkModel->where('code', '=' , $code)->where('password', '=', $password)->find();
        if (!$link) {
            throw new MissException(['msg' => '密码错误，请重试!']);
        }
        if (date2stamp($link['expire_time']) < time()) {
            throw new MissException(['msg' => '短地址已过期!']);
        }
        Record::record($code);
        return $link;
    }

    /**
     * 获取用户短地址列表
     * @access public
     * @param  int     $page           页码
     * @param  int     $size           页长
     * @return mixed                    短地址
     * @throws
     */
    public function getLinkList($page, $size)
    {
        $token = $this->getToken();
        $linkModel = new LinkModel();
        $linkModel->hidden(['expire_time'], true);
        $link = $linkModel->where('token', '=' , $token)
            ->paginate($size, false, ['page' => $page]);
        $time = time();
        $link->each(function ($item) use ($time) {
            $item->hidden(['update_time', 'delete_time', 'token'], true);
            $item['status'] = date2stamp($item['expire_time']) > $time;
        });
        return $link;
    }

    /**
     * 保存短地址
     * @access private
     * @param  string   $url        地址
     * @param  int      $expire     有效期
     * @param  string   $password   密码
     * @param  string   $token      用户ID
     * @return mixed          短地址
     * @throws
     */
    private function saveLink($url, $expire, $password, $token)
    {
        $code = $this->createOneCode();
        $link = new LinkModel;
        $link->data([
            'url' =>            $url,
            'token'  =>         $token,
            'code' =>           $code,
            'password' =>       $password,
            'expire_time' =>    stamp2date($expire)
        ]);
        $link->save();
        return $link;
    }

    /**
     * 生成唯一短地址
     * @access private
     * @param  int      $length     长度
     * @return string               code
     * @throws
     */
    private function createOneCode($length = 6)
    {
        $chars = array(
            "a", "b", "c", "d", "e", "f", "g", "h", "i",
            "j", "k", "l", "m", "n", "o", "p", "q", "r",
            "s", "t", "u", "v", "w", "x", "y", "z", "0",
            "1", "2", "3", "4", "5", "6", "7", "8", "9"
        );
        $charsLen = count($chars) - 1;
        shuffle($chars); //打乱数组顺序
        $code = '';
        for($i=0; $i<$length; $i++){
            $code .= $chars[mt_rand(0, $charsLen)];    //随机取出一位
        }
        if (LinkModel::get(['code' => $code])) {
            return $this->createOneCode($length);
        }
        return $code;
    }
}