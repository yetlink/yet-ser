<?php

namespace app\api\validate;


class ExpireValid extends BaseValidate
{
    protected $rule = [
        'expire' => 'isTimestamp'
    ];
}