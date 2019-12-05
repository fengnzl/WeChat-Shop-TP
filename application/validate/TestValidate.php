<?php
/**
 * Created by PhpStorm.
 * User: lullabies
 * Date: 2019/11/28
 * Time: 0:13
 */

namespace app\validate;


use think\Validate;

class TestValidate extends Validate
{
    protected $rule = [
        'name' => 'require|max:10',
        'email' => 'email'
    ];
}