<?php
/**
 * Created by PhpStorm.
 * User: Lullabies
 * Date: 2019/12/17
 * Time: 20:26
 */

namespace app\api\controller\v1;


use app\validate\IDMustBePositiveInt;
use app\api\service\Pay as PayService;

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

    }
}