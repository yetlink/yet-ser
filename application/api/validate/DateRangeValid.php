<?php

namespace app\api\validate;


class DateRangeValid extends BaseValidate
{
    protected $rule = [
        'date_start' => 'require|isTimestamp',
        'date_end' => 'require|isTimestamp'
    ];
}