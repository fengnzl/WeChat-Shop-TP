<?php
/**
 * Created by PhpStorm.
 * User: lullabies
 * Date: 2019/12/5
 * Time: 23:28
 */

namespace app\api\controller\v1;


use app\api\service\UserToken;
use app\validate\TokenGet;

class Token
{
    /**
     * 获取用户令牌信息
     * @url /Token/user
     */
    public function getToken($code = ''){
        (new TokenGet())->goCheck();
        $ut = new UserToken($code);
        $token = $ut->get();
        return json([
            'token' => $token
        ]);
    }
}