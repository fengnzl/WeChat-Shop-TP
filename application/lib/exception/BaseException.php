<?php
/**
 * Created by PhpStorm.
 * User: lullabies
 * Date: 2019/11/30
 * Time: 11:17
 */

namespace app\lib\exception;


use think\Exception;

class BaseException extends Exception
{
    public $code = 400;
    public $msg = '参数错误';
    public $errorCode = 10000;

    /**
     * 自定义异常类实例化时，如果传递参数则使用传递参数，否则是使用默认参数
     * @param array $params 调用自定义异常类时传递的参数
     */
    public function __construct($params = [])
    {
        if(!is_array($params)){
            return;
        }
        if(array_key_exists('code',$params)){
            $this->code = $params['code'];
        }
        if(array_key_exists('msg',$params)){
            $this->msg = $params['msg'];
        }
        if(array_key_exists('errorCode',$params)){
            $this->errorCode = $params['errorCode'];
        }
    }
}