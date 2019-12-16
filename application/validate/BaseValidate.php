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
        if(is_numeric($value) && is_int($value + 0) &&($value + 0)>0){
            return true;
        }else{
            return $field.'必须是正整数';
        }
    }

    protected function isNotEmpty($value , $rule='' ,$data='' ,$field='' ){
        if(empty($value)){
            return false;
        }else{
            return true;
        }
    }

    protected function isMobile($value , $rule='' ,$data='' ,$field=''){
        $rule = '^1(3|4|5|7|8)[0-9]\d{8}$^';
        $result = preg_match($rule, $value);
        if($result){
            return true;
        }else{
            return false;
        }
    }

    /**
     * 获取指定参数的变量值
     */
    public function getDataByRule($arrays){
        if(array_key_exists('user_id',$arrays) ||
            array_key_exists('uid', $arrays)){
            // 不允许包含user_id或者uid,防止恶意覆盖user_id外键
            throw new ParameterException([
                'msg' => '参数中包含非法的参数名user_id或者uid'
            ]);
        }
        $newArray = [];
        foreach ($this->rule as $key =>$value){
            $newArray[$key] = $arrays[$key];
        }
        return $newArray;
    }
}