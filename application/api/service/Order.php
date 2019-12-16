<?php
/**
 * Created by PhpStorm.
 * User: Lullabies
 * Date: 2019/12/12
 * Time: 15:48
 */

namespace app\api\service;

use app\api\model\OrderProduct;
use app\api\model\Product;
use app\api\model\UserAddress;
use app\lib\exception\OrderException;
use app\lib\exception\UserException;
use think\Exception;


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
        $this->products = $this->getProductsByOrder($oProducts);
        $this->uid = $uid;

        $status = $this->getOrderStatus();
        // 如果检测不通过 为了返回接口的一致性，不通过也返回订单号
        if(!$status['pass']){
            $status['order_id'] = -1;
            return $status;
        }

        // 开始创建订单
        $orderSnap = $this->snapOrder($status);
        $order = $this->createOrder($orderSnap);
        $order['pass'] = true;
        return $order;
    }

    // 生成订单
    private function createOrder($snap){
        try{
            $orderNo = self::makeOrderNo();
            $order = new \app\api\model\Order();
            $order->user_id = $this->uid;
            $order->order_no = $orderNo;
            $order->total_price = $snap['orderPrice'];
            $order->total_count = $snap['totalCount'];
            $order->snap_img = $snap['snapImg'];
            $order->snap_name = $snap['snapName'];
            $order->snap_address = $snap['snapAddress'];
            $order->snap_items = json_encode($snap['pStatus']);

            $order->save();
            // 订单主键
            $orderID = $order->id;
            $create_time = $order->create_time;

            foreach ($this->oProducts as &$p)
            {
                $p['order_id'] = $orderID;
            }

            $orderProduct = new OrderProduct();
            $orderProduct->saveAll($this->oProducts);

            return [
                'order_no' => $orderNo,
                'order_id' => $orderID,
                'create_time' => $create_time
            ];
        }catch (Exception $ex){
            throw $ex;
        }


    }
    // 生成唯一订单号  这里使用public static 是方便外部调用
    public static function makeOrderNo(){
        $yCode = ['A','B','C','D','E','F','G','H','I','J'];
        // dechex 十进制转换为16进制
        $orderSn = $yCode[intval(date('Y'))-2017].strtoupper(dechex(date('m')))
            .date('d').substr(time(),-5).substr(microtime(),2,5)
            .sprintf('%02d',rand(0,99));
        return $orderSn;
    }

    // 生成订单快照
    private function snapOrder($status){
        $snap = [
            'orderPrice' => 0,
            'totalCount' => 0,
            'pStatus' => [],
            'snapAddress' => null,
            'snapImg' => '',
            'snapName'=>'' // 订单快照名称
        ];
        $snap['orderPrice'] = $status['orderPrice'];
        $snap['totalCount'] = $status['totalCount'];
        $snap['pStatus'] = $status['pStatusArray'];
        $snap['snapAddress'] = json_encode($this->getUserAddress());
        $snap['snapName'] = $this->products[0]['name'];
        $snap['snapImg'] = $this->products[0]['main_img_url'];

        if(count($this->products)>1){
            $snap['snapName'] .= '等';
        }
        return $snap;
    }

    private function getUserAddress(){
        $userAddress = UserAddress::where('user_id','=',$this->uid)
            ->find();

        if(!$userAddress){
            throw new UserException([
                'msg'=>'用户收货地址不存在，下单失败',
                'errorCode' => 60001
            ]);
        }
    }
    /**
     * 获取订单状态，获取到详细的订单信息方便检验商品库存量
     */
    private function getOrderStatus(){
        $status = [
            'pass'=>true,
            'totalCount' => 0,
            'orderPrice'=> 0, // 订单总价格
            'pStatusArray'=>[] // 订单所有的商品信息状态，方便历史菜单查询
        ];

        foreach ($this->oProducts as $oProduct){
            $pStatus = $this->getProductStatus($oProduct['product_id'], $oProduct['count'], $this->products);
            if(!$pStatus['haveStock']){
                $status['pass'] = false;
            }
            $status['orderPrice'] += $pStatus['totalPrice'];
            $status['totalCount'] += $pStatus['count'];
            array_push($status['pStatusArray'], $pStatus);
        }
        return $status;
    }

    // 获取商品的状态信息
    private function getProductStatus($oPID, $oCount, $products){

        $pIndex = -1;

        $pStatus = [
            'id' => null, // 商品id
            'haveStock' => false, // 是否有库存
            'count' => 0, // 数量
            'name' => '', // 名称
            'totalPrice' => 0 // 当前商品订单总价格
        ];

        for($i=0;$i<count($products);$i++){
            if($oPID == $products[$i]['id']){
                $pIndex = $i;
            }
        }

        if($pIndex == -1){
            throw new OrderException([
                'msg'=> 'id为'.$oPID.'的商品不存在，创建订单失败'
            ]);
        }
        else{
            $product = $products[$pIndex];
            $pStatus['id'] = $product['id'];
            $pStatus['name'] = $product['name'];
            $pStatus['count'] = $oCount;
            $pStatus['totalPrice'] = $product['price']*$oCount;

            if($product['stock'] - $oCount >=0){
                $pStatus['haveStock'] = true;
            }
        }
        return $pStatus;
    }

    /**
     * 根据订单信息查找真实的商品信息
     */
    private function getProductsByOrder($oProducts){
//        foreach ($oProducts as $oProduct){
//            循环查询数据库，对数据库的压力很大，商品列表是不可控的，因此少用或者不用
//        }
        $oPIDs = [];
        foreach ($oProducts as $item){
            // 这里先把订单中的product_id先取出来放置在数组中
            array_push($oPIDs,$item['product_id']);
        }

        $products = Product::all($oPIDs)
            ->visible(['id','price','stock','name','main_img_url'])
            ->toArray();
        return $products;
    }
}