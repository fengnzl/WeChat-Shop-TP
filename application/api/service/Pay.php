<?php
/**
 * Created by PhpStorm.
 * User: Lullabies
 * Date: 2019/12/17
 * Time: 20:38
 */

namespace app\api\service;


use think\Exception;

class Pay
{
    // 订单主键id
    private $orderID;
    private $orderNO;

    public function __construct($orderID)
    {
        if(!$orderID){
            throw new Exception('订单号不允许为NULL');
        }
        $this->orderID = $orderID;
    }

    public function pay()
    {
        // 进行库存量检测
        $orderService = new Order();
        $status = $orderService->checkOrderStock($this->orderID);
    }
}