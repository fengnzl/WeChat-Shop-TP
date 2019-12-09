<?php
/**
 * Created by PhpStorm.
 * User: lullabies
 * Date: 2019/12/3
 * Time: 23:02
 */

namespace app\api\controller\v1;


use app\lib\exception\ProductMissException;
use app\validate\Count;
use app\validate\IDMustBePositiveInt;
use think\Controller;
use app\api\model\Product as ProductModel;
class Product extends Controller
{
    public function getRecent($count=15){
        (new Count())->goCheck();
        $products = ProductModel::getMostRecent($count);
        if($products->isEmpty()){
            throw new ProductMissException();
        }
        $products = $products->hidden(['summary']);
        return json($products);
    }

    public function getAllInCategory($id){
        (new IDMustBePositiveInt())->goCheck();
        $products = ProductModel::getProductsByCategoryID($id);
        if($products->isEmpty()){
            throw new ProductMissException();
        }
        $products = $products->hidden(['summary']);
        return json($products);
    }

    public function getOne($id){
        (new IDMustBePositiveInt())->goCheck();
        $product = ProductModel::getProductDetail($id);
        if(!$product){
            throw new ProductMissException();
        }
        return json($product);
    }
}