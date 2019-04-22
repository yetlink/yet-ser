<?php

namespace app\api\validate;


class UrlValid extends BaseValidate
{
    protected $rule = [
        'url' => 'require|url'
    ];

    protected function url($value, $rule='', $data='', $field='')
    {
        if (preg_match('/[A-Za-z0-9]{1}\.[A-Za-z0-9]{2}/', $value) >= 1) {
            return true;
        }
        return '请检查网址是否合法';
    }
}