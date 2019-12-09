<?php
/**
 * Created by PhpStorm.
 * User: Lullabies
 * Date: 2019/12/9
 * Time: 10:06
 */

namespace app\api\model;


class ProductProperty extends BaseModel
{
    protected $hidden = ['product_id','delete_time','id'];
}