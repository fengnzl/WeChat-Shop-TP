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
    public function getToken($code = ''){
        (new TokenGet())->goCheck();
        $ut = new UserToken();
        $token = $ut->get($code);
        return json($token);
    }
}