<?php
/**
 * Created by PhpStorm.
 * User: lullabies
 * Date: 2019/11/28
 * Time: 0:24
 */

namespace app\validate;


/**
 * 判断id是否是正整数的验证器
 * @package app\api\validate
 */
class IDMustBePositiveInt extends BaseValidate
{
    /**
     * 定义验证规则
     * 格式：'字段名'    => ['规则1','规则2'...]
     * @var array
     */
    protected $rule = [
        'id' => 'require|IsPositiveInt',
    ];

    /**
     * 定义错误信息
     * 格式：'字段名.规则名'    => '错误信息'
     * @var array
     */
    protected $message = [
        'id.require' => '必须填写相关id值',
    ];

}