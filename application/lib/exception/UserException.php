<?php
/**
 * Created by PhpStorm.
 * User: Lullabies
 * Date: 2019/12/10
 * Time: 10:47
 */

namespace app\lib\exception;


class UserException extends BaseException
{
    public $code = 404;
    public $msg = '用户不存在';
    public $errorCode = 60000;
}