<?php
/**
 * Created by PhpStorm.
 * User: Lullabies
 * Date: 2019/12/17
 * Time: 20:26
 */

namespace app\api\controller\v1;


use app\api\service\WxNotify;
use app\validate\IDMustBePositiveInt;
use app\api\service\Pay as PayService;

require_once(EXTEND_PATH.'WxPay'.DS.'WxPay.Config.php');
class Pay extends BaseController
{
    protected $beforeActionList=[
        'checkExclusionScope' => ['only'=>'getPreOrder']
    ];
    // 获取订单号
    public function getPreOrder($id=""){
        (new IDMustBePositiveInt())->goCheck();
        $pay = new PayService($id);
        return $pay->pay();
    }
    // 获取支付通知
    public function receiveNotify()
    {
        // 通知频率为15/15/30/180/1800/1800/1800/3600
        // 只有返回正确的处理消息就会停止访问
        //1. 检测库存量，超卖
        //2. 更新这个订单的 status 状态
        //3. 减库存
        $xmlData = file_get_contents('php://input');
        $result = curl_post_raw('http://wxtp.io/api/v1/pay/re_notify',$xmlData);
//        $config = new \WxPayConfig();
//        $notify = new WxNotify();
//        $notify->Handle($config);
    }

    // 回调转发接口
    public function redirectNotify()
    {
        // 通知频率为15/15/30/180/1800/1800/1800/3600
        // 只有返回正确的处理消息就会停止访问
        //1. 检测库存量，超卖
        //2. 更新这个订单的 status 状态
        //3. 减库存
        $config = new \WxPayConfig();
        $notify = new WxNotify();
        $notify->Handle($config);
    }
}