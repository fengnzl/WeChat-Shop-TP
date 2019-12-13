<?php
/**
 * Created by PhpStorm.
 * User: Lullabies
 * Date: 2019/12/11
 * Time: 15:51
 */

namespace app\api\controller\v1;


use app\validate\OrderPlace;
use app\api\service\Token as TokenService;
class Order extends BaseController
{
    // 前置方法
    protected $beforeActionList = [
        'checkExclusionScope' => ['only'=> 'placeOrder']
    ];

    public function placeOrder(){
        (new OrderPlace())->goCheck();
        // 这里a修饰符是将传入的变量转换为数组类型，默认为字符串类型
        $products = input('post.products/a');
        $uid = TokenService::getCurrentUid();
    }
}