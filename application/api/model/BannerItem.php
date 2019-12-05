<?php

namespace app\api\model;

class BannerItem extends BaseModel
{
    protected $hidden = ['id','img_id','delete_time','update_time','banner_id'];
    public function image(){
        return $this->belongsTo('Image','img_id','id');
    }
}
