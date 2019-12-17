<?php
/**
 * Created by PhpStorm.
 * User: Lullabies
 * Date: 2019/12/17
 * Time: 20:26
 */

namespace app\api\controller\v1;


use app\validate\IDMustBePositiveInt;

class Pay extends BaseController
{
    protected $beforeActionList=[
        'checkExclusionScope' => ['only'=>'getPreOrder']
    ];
    // 获取订单号
    public function getPreOrder($id=""){
        (new IDMustBePositiveInt())->goCheck();
    }
}