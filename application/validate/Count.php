<?php
/**
 * Created by PhpStorm.
 * User: lullabies
 * Date: 2019/12/3
 * Time: 23:03
 */

namespace app\validate;


class Count extends BaseValidate
{
    protected $rule =[
        'count' => 'IsPositiveInt|between:1,15'
    ];
}