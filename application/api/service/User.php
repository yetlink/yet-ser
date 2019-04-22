<?php
/**
 * Created by PhpStorm.
 * User: Brandon
 */

namespace app\api\service;


use app\api\model\User as UserModel;

use think\Request;

class User extends BaseService
{

    protected $request = null;

    public function __construct()
    {
        $this->request = Request::instance();
    }

    /**
     * 创建用户
     * @access public
     * @return array  UserToken
     * @throws
     */
    public function login()
    {
        $token = $this->createToken();
        $user = new UserModel();
        $user->data([
            'token'  =>  $token,
            'ip' =>  $this->request->ip(),
            'agent' => $_SERVER['HTTP_USER_AGENT']
        ]);
        $user->save();
        return ['token' => $token];
    }

    /**
     * 生成用户唯一Token
     * @access private
     * @return string   Token
     * @throws
     */
    private function createToken()
    {
        $token = strtoupper(md5($this->request->domain() . '_' .$this->request->ip() . '_' . time()));
        if (UserModel::get(['token' => $token])) {
            return $this->createToken();
        }
        return $token;
    }
}