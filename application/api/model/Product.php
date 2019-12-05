<?php
/**
 * Created by PhpStorm.
 * User: lullabies
 * Date: 2019/12/3
 * Time: 22:22
 */

namespace app\api\model;


class Product extends BaseModel
{
    protected $hidden = ['create_time','update_time','pivot','category_id','img_id','delete_time','from'];

    public function getMainImgUrlAttr($value, $data){
        return $this->prefixImgUrl($value, $data);
    }

    public static function getMostRecent($count){
        $products = self::limit($count)
            ->order('create_time desc')
            ->select();
        return $products;
    }

    public static function getProductsByCategoryID($categoryID){
        return self::where('category_id','=',$categoryID)->select();
    }
}