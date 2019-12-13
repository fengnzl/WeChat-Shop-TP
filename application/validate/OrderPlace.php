<?php
/**
 * Created by PhpStorm.
 * User: Lullabies
 * Date: 2019/12/12
 * Time: 15:08
 */

namespace app\validate;


use app\lib\exception\ParameterException;

class OrderPlace extends BaseValidate
{
    protected $rule = [
        'products' => 'checkProducts'
    ];

    protected function checkProducts($values){
        if(empty($vallus)){
            throw new ParameterException([
                'msg' => '商品列表不能为空'
            ]);
        }

        if(!is_array($values)){
            throw new ParameterException([
                'msg' => '商品参数不正确'
            ]);
        }

        foreach ($values as $value){
            $this->checkProduct($value);
        }

        return true;
    }

    protected $singleRule = [
        'product_id' => 'require|isPositiveInt',
        'count' => 'require|isPositiveInt'
    ];

    protected function checkProduct($value){
        $validate = new BaseValidate($this->singleRule);
        $result = $validate->check($value);

        if(!$result){
            throw new ParameterException([
                'msg' => '商品参数错误'
            ]);
        }
    }
}