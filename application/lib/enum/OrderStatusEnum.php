<?php
/**
 * Created by PhpStorm.
 * User: Lullabies
 * Date: 2019/12/18
 * Time: 16:43
 */

namespace app\lib\enum;


class OrderStatusEnum
{
    //待支付
    const UNPAID =1;
    // 已支付
    const PAID = 2;
    // 已发货
    const DELIVERED = 3;
    // 已支付，但库存量不足
    const PAID_BUT_OUT_OFF = 4;
}