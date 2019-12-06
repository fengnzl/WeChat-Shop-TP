<?php
/**
 * Created by PhpStorm.
 * User: Lullabies
 * Date: 2019/12/6
 * Time: 17:18
 */

namespace app\lib\exception;


class TokenException extends BaseException
{
    public $code = 401;
    public $msg = 'Token已过期或无效Token';
    public $errorCode = 10001;
}