<?php
/**
 * Created by PhpStorm.
 * User: lullabies
 * Date: 2019/12/5
 * Time: 23:29
 */

namespace app\validate;


class TokenGet extends BaseValidate
{
    protected $rule = [
        'code' => 'require|isNotEmpty'
    ];

    protected $message = [
        'code' => '没有code无法获取Token'
    ];
}