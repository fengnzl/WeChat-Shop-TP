<?php
/**
 * Created by PhpStorm.
 * User: lullabies
 * Date: 2019/12/2
 * Time: 23:03
 */

namespace app\api\model;


use think\Model;

class BaseModel extends Model
{
    public function prefixImgUrl($value, $data){
        $finalUrl = $value;
        if($data['from'] == 1){
            $finalUrl = config('setting.img_prefix').$value;
        }
        return $finalUrl;
    }
}