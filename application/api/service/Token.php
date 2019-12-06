<?php
/**
 * Created by PhpStorm.
 * User: Lullabies
 * Date: 2019/12/6
 * Time: 16:36
 */

namespace app\api\service;


class Token
{
    public static function generateToken(){
        // 选取32个字符组成一组随机字符串
        $randomChars = getRandomChars(32);
        // 为了安全性， 用三组字符串 进行md5加密
        $timestamp = $_SERVER['REQUEST_TIME_FLOAT'];
        // salt 盐 特殊的加密信息
        $salt = config('secure.token_salt');
        return md5($randomChars.$timestamp.$salt);
    }
}