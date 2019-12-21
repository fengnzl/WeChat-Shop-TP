<?php
/**
 * Created by PhpStorm.
 * User: Lullabies
 * Date: 2019/12/11
 * Time: 15:51
 */

namespace app\api\controller\v1;


use app\lib\exception\OrderException;
use app\validate\IDMustBePositiveInt;
use app\validate\OrderPlace;
use app\api\service\Token as TokenService;
use app\api\service\Order as OrderService;
use app\validate\PagingParameter;
use app\api\model\Order as OrderModel;

class Order extends BaseController
{
    // 前置方法
    protected $beforeActionList = [
        'checkExclusionScope' => ['only'=> 'placeOrder'],
        'checkPrimaryScope' => ['only'=>'getDetail,getSummaryByUser']
    ];
    // 获取订单详情
    public function getDetail($id){
        (new IDMustBePositiveInt())->goCheck();
        $orderDetail = OrderModel::get($id);
        if(!$orderDetail){
            throw new OrderException();
        }
        return json($orderDetail->hidden(['prepay_id']));
    }
    // 获取用户的简要订单信息 管理员也可以查看用户的订单信息
    public function getSummaryByUser($page=1, $size=15)
    {
        (new PagingParameter())->goCheck();
        $uid = TokenService::getCurrentUid();
        $pagingOrders = OrderModel::getSummaryByuser($uid, $page, $size);
        if($pagingOrders->isEmpty())
        {
            return json([
                'data'=>[],
                'current_page'=>$pagingOrders->getCurrentPage()
            ]);
        }
        $data = $pagingOrders ->hidden(['snap_item','snap_address','prepay_id'])->toArray();
        return json([
            'data'=>$data,
            'current_page'=>$pagingOrders->getCurrentPage()
        ]);
    }

    // 下单接口
    public function placeOrder()
    {
         /*products = [
            [
                'product_id'=>1,
                'count'=>2
            ]
         ]*/
        (new OrderPlace())->goCheck();
        // 这里a修饰符是将传入的变量转换为数组类型，默认为字符串类型
        $products = input('post.products/a');
        $uid = TokenService::getCurrentUid();
        $order = new OrderService();
        $status = $order->place($uid, $products);
        return json($status);
    }
}