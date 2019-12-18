<?php
/**
 * Created by PhpStorm.
 * User: Lullabies
 * Date: 2019/12/17
 * Time: 20:38
 */

namespace app\api\service;


use app\api\model\Order as OrderModel;
use app\api\service\Order as OrderService;
use app\lib\enum\OrderStatusEnum;
use app\lib\exception\OrderException;
use app\lib\exception\TokenException;
use think\Exception;
use think\Loader;
use think\Log;

// extend/WxPay/WxPay.WxPay.Api.php
Loader::import('WxPay.WxPay',EXTEND_PATH,'.Api.php');

require_once(EXTEND_PATH.'WxPay'.DS.'WxPay.Config.php');

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
        //1. 订单号可能不存在（在控制器中执行进行了基本变量类型的检测，而没有进行业务逻辑的检测）
        //2. **订单号存在，但是订单号和当前用户不匹配**
        //3. 订单可能已经被支付
        //4. 库存量检测

        $this->checkOrderValidate();
        // 进行库存量检测
        $orderService = new OrderService();
        $status = $orderService->checkOrderStock($this->orderID);
        if(!$status['pass']){
            return $status;
        }
        return $this->makeWxPreOrder($status['orderPrice']);
    }

    // 微信预下单
    private function makeWxPreOrder($totalPrice)
    {
        $openid = Token::getCurrentTokenVar('openid');
        if(!$openid){
            throw new TokenException();
        }

        $wxOrderData = new \WxPayUnifiedOrder();
        // 设置订单号
        $wxOrderData->SetOut_trade_no($this->orderNO);
        //设置交易类型
        $wxOrderData->SetTrade_type('JSAPI');
        // 设置交易金额 单位为分
        $wxOrderData->SetTotal_fee($totalPrice*100);
        // 设置订单描述
        $wxOrderData->SetBody('零食商贩');
        // 设置用户身份标识
        $wxOrderData->SetOpenid($openid);
        // 设置回调地址 这里需要编写方法来接受小程序返回的回调地址
//        $wxOrderData->SetNotify_url("");
        $wxOrderData->SetNotify_url("https://www.baidu.com");
        return $this->getPaySignature($wxOrderData);

    }
    // 获取签名
    private function getPaySignature($wxOrderData){
        $config = new \WxPayConfig();
        // 向微信统一下单
        $wxOrder = \WxPayApi::unifiedOrder($config, $wxOrderData);
        // 判断接口是否调用成功
        if($wxOrder['return_code']!='SUCCESS' ||
            $wxOrder['result_code'] != 'SUCCESS')
        {// 记录日志
            Log::record($wxOrder, 'error');
            Log::record('获取预支付订单失败','error');
        }
        $this->recordPrepayID($wxOrder);
        $signature = $this->sign($wxOrder);
        return $signature;
    }

    // 生成支付数据的签名及相关支付参数
    private function sign($wxOrder){
        $jsApiPayData = new \WxPayJsApiPay();
        // 相关参数文档 https://pay.weixin.qq.com/wiki/doc/api/wxa/wxa_api.php?chapter=7_7&index=3
        $jsApiPayData->SetAppid(config('ex.app_id'));
        $jsApiPayData->SetTimeStamp(string(time()));
        // 生成随机串
        $rand = md5(time().mt_rand(0,1000));
        $jsApiPayData->SetTimeStamp($rand);

        $jsApiPayData->SetPackage('prepay_id='.$wxOrder['prepay_id']);
        $jsApiPayData->SetSignType('MD5');
        // 微信封装的生成sign方法
        $sign = $jsApiPayData->MakeSign();
        // 获取参数数组
        $rawValues = $jsApiPayData->GetValues();
        // 将生成的签名添加到数组中
        $rawValues['paySign'] = $sign;
        // 因为数组中还携带appid 而客户端并不用appid这个参数
        // 而且有些时候我们也不希望返回这个参数，因袭我们将其删除
        unset($rawValues['appId']);
        return $rawValues;

    }
    // 保存prepay_id
    private function recordPrepayID($wxOrder){
        OrderModel::where('id','=',$this->orderID)
            ->update(['prepay_id'=>$wxOrder['prepay_id']]);
    }
    // 检测订单是否符合业务逻辑
    private function checkOrderValidate(){
        // 查询当前订单号的相关信息
        $order = OrderModel::where('id','=',$this->orderID)
            ->find();
        // 如果订单不存在
        if(!$order) {
            throw new OrderException();
        }
        // 查看是否与当前用户匹配
        if(!Token::isValidOperate($order->user_id)){
            throw new TokenException([
                'msg'=>'订单与用户不匹配',
                'errorCode'=>10003
            ]);
        }
        // 检测订单是否已经被支付
        if($order->status != OrderStatusEnum::UNPAID){
            throw new OrderException([
                'msg'=>'该笔订单已被支付',
                'errorCode' => 80003,
                'code' => 400
            ]);
        }
        $this->orderNO = $order->order_no;
        return true;
    }
}