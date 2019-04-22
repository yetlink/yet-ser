<?php

namespace app\api\validate;


class IdValid extends BaseValidate
{
    protected $rule = [
        'Id' => 'isInteger'
    ];
}