<?php
/**
 * Created by PhpStorm.
 * User: Brandon
 */

namespace app\api\controller\v1;

use app\api\controller\BaseController;
use app\api\service\Link as LinkService;
use app\api\service\User as UserService;
use app\api\service\Record as RecordService;
use app\api\validate\IdValid;
use app\api\validate\UrlValid;
use app\api\validate\CodeValid;
use app\api\validate\ExpireValid;
use app\api\validate\DateRangeValid;
use app\api\validate\PagingParameter;

class Yet extends BaseController
{

    // 创建用户
    public function login()
    {
        return (new UserService())->login();
    }

    // 创建短地址
    public function createLink($url = '', $expire = 0, $password = '')
    {
        (new UrlValid())->goCheck();
        (new ExpireValid())->goCheck();
        $service = new LinkService();
        return $service->createLink($url, $expire, $password);
    }

    public function getLongUrl($code = '')
    {
        (new CodeValid())->goCheck();
        $service = new LinkService();
        return $service->getByCode($code);
    }

    // 使用密码获取长地址
    public function getByPassword($code = '', $password = '')
    {
        (new CodeValid())->goCheck();
        $service = new LinkService();
        return $service->getByPassword($code, $password);
    }

    // 获取用户短地址列表
    public function getLinkList($page = 1, $size = 20)
    {
        (new PagingParameter())->goCheck();
        $service = new LinkService();
        return $service->getLinkList($page, $size);
    }

    // 获取短地址记录
    public function getLinkRecord($code = '')
    {
        (new CodeValid())->goCheck();
        $val = new DateRangeValid();
        $val->goCheck();
        $date = $val->getDataByRule(input('get.'));
        $service = new RecordService();
        return $service->getLinkRecord($code, $date['date_start'], $date['date_end']);
    }

    // 修改短地址
    public function modifyLink($id = 0, $expire = 0, $password = '')
    {
        (new IdValid())->goCheck();
        (new ExpireValid())->goCheck();
        $service = new LinkService();
        return $service->modifyLink($id, $expire, $password);
    }
}