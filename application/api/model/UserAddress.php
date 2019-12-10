<?php
/**
 * Created by PhpStorm.
 * User: Lullabies
 * Date: 2019/12/10
 * Time: 10:51
 */

namespace app\api\model;


class UserAddress extends BaseModel
{
    protected $hidden = ['id','delete_time','user_id'];
}