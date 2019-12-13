<?php
/**
 * Created by PhpStorm.
 * User: Lullabies
 * Date: 2019/12/12
 * Time: 15:48
 */

namespace app\api\service;


class Order
{
    // 订单的商品列表，也就是客户端传递的products参数
    protected $oProducts;

    //真实的商品信息（包括库存量）
    protected $products;

    protected $uid;

    /**
     * 下单函数
     */
    public function place($uid, $oProducts){
        // oProducts和products进行对比
        // products 从数据库中查询
        $this->oProducts = $oProducts;

    }
}