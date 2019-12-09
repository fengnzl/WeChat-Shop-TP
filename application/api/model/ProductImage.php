<?php
/**
 * Created by PhpStorm.
 * User: Lullabies
 * Date: 2019/12/9
 * Time: 10:06
 */

namespace app\api\model;


class ProductImage extends BaseModel
{
    protected $hidden = ['delete_time','img_id','product_id'];
    public function imgUrl(){
        return $this->belongsTo('Image','img_id','id');
    }
}