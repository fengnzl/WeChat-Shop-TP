<?php

namespace app\api\model;

class Image extends BaseModel
{
    protected $hidden = ['id','from','delete_time','update_time'];

    /**
     * 读取器 驼峰命名  get固定字段 Url读取的字段名 Attr字段值 data该记录的所有字段
     * @param $value
     * @param $data
     * @return string
     */
    public function getUrlAttr($value,$data){
        return $this->prefixImgUrl($value, $data);
    }
}
