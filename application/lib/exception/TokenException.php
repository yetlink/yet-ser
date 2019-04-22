<?php
/**
 * token异常.
 */

namespace app\lib\exception;

/**
 * 404时抛出此异常
 */
class TokenException extends BaseException
{
    public $code = 401;
    public $msg = 'Token已过期或无效Token';
    public $errorCode = 10001;
}