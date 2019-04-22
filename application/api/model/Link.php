<?php

namespace app\api\model;

use think\Request;

class Link extends BaseModel
{

    protected $append = [
        'sort_url',
    ];

    public function getSortUrlAttr($value, $data)
    {
        return Request::instance()->domain().'/'.$data['code'];
    }

    // 一对多 浏览记录
    public function recordList()
    {
        return $this->hasMany('Record', 'code', 'code');
    }
}