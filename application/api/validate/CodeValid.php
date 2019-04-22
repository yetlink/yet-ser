<?php

namespace app\api\validate;


class CodeValid extends BaseValidate
{
    protected $rule = [
        'code' => 'code'
    ];

    protected function code($value, $rule='', $data='', $field='')
    {
        if ($value && strlen($value) == 6) {
            return true;
        }
        return '非法短网址';
    }
}