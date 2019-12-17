<?php
/**
 * Created by PhpStorm.
 * User: lullabies
 * Date: 2019/12/16
 * Time: 23:03
 */

namespace app\api\model;


class Order extends BaseModel
{
    protected $hidden = ['user_id','delete_time','update_time'];
    // 设置自动写入事件戳
    protected $autoWriteTimestamp = true;

    // 指定创建时间
//    protected $createTime = 'createtime';
}