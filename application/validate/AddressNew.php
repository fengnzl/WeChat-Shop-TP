<?php
/**
 * Created by PhpStorm.
 * User: Lullabies
 * Date: 2019/12/10
 * Time: 9:55
 */

namespace app\validate;


class AddressNew extends BaseValidate
{
    protected $rule =  [
        'name'=>'require|isNotEmpty',
        'mobile'=>'require|isMobile',
        'province'=>'require|isNotEmpty',
        'city'=>'require|isNotEmpty',
        'country'=>'require|isNotEmpty',
        'detail'=>'require|isNotEmpty',
    ];

}