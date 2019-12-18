<?php
/**
 * Created by PhpStorm.
 * User: Lullabies
 * Date: 2019/12/6
 * Time: 16:36
 */

namespace app\api\service;


use app\lib\enum\ScopeEnum;
use app\lib\exception\ForbiddenException;
use app\lib\exception\TokenException;
use think\Cache;
use think\Exception;
use think\Request;

class Token
{
    // 检测当前操作是否合法，如订单号的用户id和当前令牌里的uid是否相等
    public static function isValidOperate($checkUID)
    {
        if(!$checkUID){
            throw new Exception('检查UID时必须传入一个被检查的UID');
        }
        $uid = self::getCurrentUid();
        if($uid == $checkUID){
            return true;
        }
        return false;
    }

    // 生成Token令牌
    public static function generateToken(){
        // 选取32个字符组成一组随机字符串
        $randomChars = getRandomChars(32);
        // 为了安全性， 用三组字符串 进行md5加密
        $timestamp = $_SERVER['REQUEST_TIME_FLOAT'];
        // salt 盐 特殊的加密信息
        $salt = config('secure.token_salt');
        return md5($randomChars.$timestamp.$salt);
    }

    public static function getCurrentTokenVar($key){
        // 所有的token都应通过http的header传递，而不是body
        $token = Request::instance()->header('token');
        // 获取缓存中的数据
        $vars = Cache::get($token);
        if(!$vars){// 缓存已过期或者缓存异常
            throw new TokenException();
        }else{
            if(!is_array($vars)){
                $vars = json_decode($vars, true);
            }
            if(array_key_exists($key, $vars)){
                return $vars[$key];
            }else{
                throw new Exception('尝试获取的Token变量不存在');
            }
        }
    }
    /**
     * 根据token获取uid
     */
    public static function getCurrentUid(){
        $uid = self::getCurrentTokenVar('uid');
        return $uid;
    }

    /**
     * 用户和cms管理员均可访问的权限
     */
    public static function needPrimaryScope(){
        $scope = self::getCurrentTokenVar('scope');
        if($scope>=ScopeEnum::User){
            return true;
        }else{
            throw new ForbiddenException();
        }
    }

    /**
     * 只有用户可访问的权限
     */
    public static function needExclusionScope(){
        $scope = self::getCurrentTokenVar('scope');
        if($scope==ScopeEnum::User){
            return true;
        }else{
            throw new ForbiddenException();
        }
    }
}