<?php
/**
 * Created by PhpStorm.
 * User: lullabies
 * Date: 2019/12/1
 * Time: 19:50
 */

namespace app\lib\exception;


class BannerMissException extends BaseException
{
    public $code = 404;
    public $msg = '请求的Banner不存在';
    public $errorCode = 40000;
}