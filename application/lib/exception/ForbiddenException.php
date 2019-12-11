<?php
/**
 * Created by PhpStorm.
 * User: Lullabies
 * Date: 2019/12/11
 * Time: 13:57
 */

namespace app\lib\exception;


class ForbiddenException extends BaseException
{
    public $code = 403;
    public $msg = '权限不够';
    public $errorCode = 10001;
}