<?php
/**
 * Created by PhpStorm.
 * User: lullabies
 * Date: 2019/12/3
 * Time: 22:33
 */

namespace app\lib\exception;


class ThemeMissException  extends BaseException
{
    public $code = 404;
    public $msg ='指定主题不存在，请检查主题ID';
    public $errorCode = 30000;
}