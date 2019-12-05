<?php
/**
 * Created by PhpStorm.
 * User: lullabies
 * Date: 2019/12/3
 * Time: 23:28
 */

namespace app\lib\exception;


class CategoryMissException extends BaseException
{
    public $code = 404;
    public $msg ="指定类目不存在，请检查参数";
    public $errorCode = 50000;
}