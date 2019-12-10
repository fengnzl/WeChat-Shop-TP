<?php
/**
 * Created by PhpStorm.
 * User: Lullabies
 * Date: 2019/12/10
 * Time: 10:55
 */

namespace app\lib\exception;


class SuccessMessage extends BaseException
{
    public $code = 201;// 资源修改成功
    public $msg = 'ok';
    public $errorCode = 0;
}