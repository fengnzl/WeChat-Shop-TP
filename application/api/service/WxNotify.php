<?php
/**
 * Created by PhpStorm.
 * User: lullabies
 * Date: 2019/12/19
 * Time: 0:27
 */

namespace app\api\service;

use think\Exception;
use think\Loader;
use app\api\model\Order as OrderModel;
use app\api\service\Order as OrderService;

Loader::import('WxPay.WxPay',EXTEND_PATH,'.notify.php');
class WxNotify extends \WxPayNotify
{
    public function NotifyProcess($objData, $config, &$msg)
    {
        if($objData['result_code'] == 'SUCCESS'){
            $orderNo = $objData['trade_order_no'];
            try{
                $order = OrderModel::where('order_no','=',$orderNo)
                    ->find();
                if($order->status == 1){
                    $orderService = new OrderService();
                    $status = $orderService->checkOrderStock($order->id);
                    if($status['pass'] == true){
                        // 更新订单状态
                        $this->updateStatus();
                        // 减少库存
                        $this->reduceStock();
                    }
                }
            }
            catch(Exception $e)
            {

            }
        }
    }
}