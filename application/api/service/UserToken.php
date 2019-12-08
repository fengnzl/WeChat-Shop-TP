<?php
/**
 * Created by PhpStorm.
 * User: lullabies
 * Date: 2019/12/5
 * Time: 23:35
 */

namespace app\api\service;


use app\lib\exception\TokenException;
use app\lib\exception\WeChatException;
use think\Exception;
use app\api\model\User as UserModel;
class UserToken extends Token
{
    protected $code;
    protected $wxAppID;
    protected $wxAppSecret;
    protected $wxLoginUrl;

    public function __construct($code){
        $this->code = $code;
        $this->wxAppID = config('wx.app_id');
        $this->wxAppSecret = config('wx.app_secret');
        $this->wxLoginUrl = sprintf(config('wx.login_url'), $this->wxAppID,$this->wxAppSecret,$this->code);
    }

    public function get(){
        $result = curl_get($this->wxLoginUrl);
        $wxResult = json_decode($result, true);

        if(empty($wxResult)){
            throw new Exception('获取openid及session_key异常，微信内部错误');
        }else{
            $loginFail = array_key_exists('errcode', $wxResult);
            if($loginFail){
                $this->processLoginError($wxResult);
            }else{
                // 调用授权接口
                 return $this->grantToken($wxResult);

            }
        }
    }

    /**
     * 颁发令牌接口
     */
    private function grantToken($wxResult){
        // 拿到openid->查询数据库，不存在则新增记录->生成令牌，准备缓存数据，写入缓存->令牌返回至客户端
        $openid = $wxResult['openid'];
        // 第二步查询数据库
        $user = UserModel::getByOpenID($openid);
        if($user){
            $uid = $user->id;
        }else{
            $uid = $this->newUser($openid);
        }
        // 第三步 存缓存 key: 令牌  value: wxResult,uid(代表用户唯一身份),scope(决定用户身份)
        $cachedValue = $this->prepareCachedValue($wxResult, $uid);

        $token = $this->saveToCache($cachedValue);
        return $token;
    }

    private function saveToCache($cachedValue){
        // 随机生成字符串
        $key = self::generateToken();
        // 将数组转为字符串
        $value = json_encode($cachedValue);
        //过期时间
        $expire_in = config('setting.token_expire_in');
        //TP5封装的缓存函数 默认的是文件缓存
        $request = cache($key, $value, $expire_in);
        if(!$request){
            throw new TokenException([
                'msg' => '服务器缓存异常',
                'errorCode' => 10005,
            ]);
        }
        return $key;
    }

    /**
     * 准备存入缓存的数据
     * 这里scope代表权限  是一串整型数字，数字越大，权限越大
     */
    private function prepareCachedValue($wxResult, $uid){
        $cachedValue = $wxResult;
        $cachedValue['uid'] = $uid;
        $cachedValue['scope'] = 16;
        return $cachedValue;
    }
    /**
     * 将新用户保存到数据库
     */
    private function newUser($openid){
        $user = UserModel::create([
            'openid'=>$openid
        ]);
        return $user->id;
    }

    private function processLoginError($wxResult){
        throw new WeChatException([
            'msg' => $wxResult['errmsg'],
            'errorCode' => $wxResult['errcode'],
        ]);

    }


}