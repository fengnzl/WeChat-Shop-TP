<?php
/**
 * Created by PhpStorm.
 * User: lullabies
 * Date: 2019/12/3
 * Time: 23:18
 */

namespace app\api\model;


class Category extends BaseModel
{
    protected $hidden=['delete_time','update_time','topic_img_id'];

    public function img(){
        return $this->belongsTo('Image','topic_img_id'.'id');
    }
}