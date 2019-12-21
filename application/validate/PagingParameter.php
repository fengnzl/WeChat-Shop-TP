<?php
/**
 * Created by PhpStorm.
 * User: lullabies
 * Date: 2019/12/21
 * Time: 21:27
 */

namespace app\validate;


class PagingParameter extends BaseValidate
{
    protected $rule = [
        'page' => 'isPositiveInt',
        'size' => 'isPositiveInt'
    ];
    protected $message = [
        'page' => '分页参数必须是正整数',
        'size' => '分页参数必须是正整数'
    ];
}