<?php
/**
 * Created by PhpStorm.
 * User: lullabies
 * Date: 2019/12/3
 * Time: 22:24
 */

namespace app\validate;


class IDCollection extends BaseValidate
{
    protected $rule =[
        'ids' =>'require|checkIDs'
    ];
    protected $message =[
        'ids'=>'ids参数必须是以逗号分隔的正整数'
    ];
    protected function checkIDs($value){
        $values = explode(',', $value);
        if(empty($values)){
            return false;
        }
        foreach ($values as $id){
            if(!$this->IsPositiveInt($id)){
                return false;
            }
        }
        return true;
    }
}