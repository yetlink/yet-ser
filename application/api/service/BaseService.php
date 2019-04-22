<?php
/**
 * Created by PhpStorm.
 * User: Brandon
 */

namespace app\api\service;

use app\lib\exception\TokenException;
use think\Request;

class BaseService
{
    /**
     * 获取当前用户的uid
     * @access public
     * @return int       用户uid
     * @throws
     */
    protected function getToken()
    {
        $token = Request::instance()->header('token');
        if (!$token) {
            throw new TokenException();
        }
        return $token;
    }

}