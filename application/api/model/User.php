<?php
/**
 * Created by PhpStorm.
 * User: lullabies
 * Date: 2019/12/5
 * Time: 23:34
 */

namespace app\api\model;


class User extends BaseModel
{
    public function address(){
        return $this->hasOne('UserAddress', 'user_id','id');
    }
    public static function getByOpenID($openid){
        $user = self::where('openid','=', $openid)
            ->find();
        return $user;
    }
}