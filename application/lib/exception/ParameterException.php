<?php
/**
 * Created by PhpStorm.
 * User: lullabies
 * Date: 2019/11/30
 * Time: 11:47
 */

namespace app\lib\exception;


class ParameterException extends BaseException
{
    public $code = '400';
    public $msg = '参数错误';
    public $errorCode = 10000;
}