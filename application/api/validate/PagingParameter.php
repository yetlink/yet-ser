<?php

namespace app\api\validate;


class PagingParameter extends BaseValidate
{
    protected $rule = [
        'page' => 'isInteger',
        'size' => 'isInteger'
    ];

    protected $message = [
        'page' => '分页参数必须是整数',
        'size' => '分页参数必须是整数'
    ];
}