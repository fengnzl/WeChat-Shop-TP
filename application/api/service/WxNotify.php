<?php
/**
 * Created by PhpStorm.
 * User: lullabies
 * Date: 2019/12/19
 * Time: 0:27
 */

namespace app\api\service;

use app\api\model\Product;
use app\lib\enum\OrderStatusEnum;
use think\Db;
use think\Exception;
use think\Loader;
use app\api\model\Order as OrderModel;
use app\api\service\Order as OrderService;
use think\Log;

Loader::import('WxPay.WxPay',EXTEND_PATH,'.Api.php');
class WxNotify extends \WxPayNotify
{
    public function NotifyProcess($objData, $config, &$msg)
    {
        if($objData['result_code'] == 'SUCCESS')
        {
            $orderNo = $objData['trade_order_no'];
            Db::startTrans();
            try
            {
                $order = OrderModel::where('order_no','=',$orderNo)
                    ->find();
                if($order->status == 1)
                {
                    $orderService = new OrderService();
                    $stockStatus = $orderService->checkOrderStock($order->id);
                    if($stockStatus['pass'])
                    { // 库存量检测成功
                        // 更新订单状态
                        $this->updateStatus($order->id, true);
                        // 减少库存
                        $this->reduceStock($stockStatus);
                    }
                    else
                    {
                        $this->updateStatus($order->id,false);
                    }
                }
                Db::commit();
                return true;
            }
            catch(Exception $e)
            {
                Db::rollback();
                Log::error($e);// 错误信息记录日志
                return false;
            }
        }
        else
        { // 订单支付失败，返回true，让微信不在调用通知接口
            return true;
        }
    }

    // 更新订单状态
    private function updateStatus($orderID, $success) {
        $status = $success ? OrderStatusEnum::PAID
            : OrderStatusEnum::PAID_BUT_OUT_OFF;
        OrderModel::where('id','=',$orderID)
            ->update(['status'=>$status]);
    }
    // 减少库存
    private function reduceStock($stockStatus){
        // 订单商品状态信息
        foreach ($stockStatus['pStatusArray'] as $singlePStatus){
            Product::where('id','=',$singlePStatus['id'])
                ->setDec('stock',$singlePStatus['count']);
        }
    }
}