<?php
/**
 * Created by PhpStorm.
 * User: lullabies
 * Date: 2019/12/1
 * Time: 18:45
 */

namespace app\api\model;


class Banner extends BaseModel
{
    protected $hidden = ['update_time','delete_time'];
    /**
     * 一堆多关联 banner表与banner_item表形成一对多关联的方法
     * return 关联结果的对象
     */
    public function items(){
        return $this->hasMany('BannerItem','banner_id','id');
    }
    public static function getBannerByID($id){
        return self::with(['items','items.image'])->where('id','=',$id)->select();
    }
}