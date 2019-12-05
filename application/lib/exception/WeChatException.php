<?php
/**
 * Created by PhpStorm.
 * User: lullabies
 * Date: 2019/12/6
 * Time: 1:00
 */

namespace app\lib\exception;


class WeChatException extends BaseException
{
    public $code =404;
    public $msg="微信服务器接口调用失败";
    public $errorCode = 999;
}