<?php
/**
 * Created by PhpStorm.
 * User: lullabies
 * Date: 2019/11/28
 * Time: 0:33
 */

namespace app\validate;


use app\lib\exception\ParameterException;
use think\Exception;
use think\Validate;

class BaseValidate extends Validate
{
    /**
     * 对http传递的参数进行验证
     * @return bool  true
     * @throws Exception 验证的错误信息
     */
    public function goCheck(){
        // 获取Http传递的所有参数，并对参数进行校验
        $params = \request()->param();
        // 调用验证器中的check()和batch()方法对参数进行批量验证
        $result = $this->batch()->check($params);
        // 判断验证是否通过
        if(!$result){
            $e = new ParameterException([
                'msg' => $this->error,
            ]);
//            $e->msg = $this->error;
            throw $e;
            /* // 未设置全局异常处理的情况下直接抛出错误9
            $error = $this->getError();
            throw new Exception($error);*/
        }else{
            return true;
        }
    }

    /**
     * 自定义验证规则 判断id必须为正整数
     */
    //                           校验字段的值  校验规则  传递的数据   校验的字段
    protected function IsPositiveInt($value , $rule='' ,$data='' ,$field='' ){
        if(is_numeric($value) && intval($value).'' === $value&&($value + 0)>0){
            return true;
        }else{
            return $field.'必须是正整数';
        }
    }

    protected function isNotEmpty($value , $rule='' ,$data='' ,$field='' ){
    if(empty($value)){
        return true;
    }else{
        return false;
    }
}
}