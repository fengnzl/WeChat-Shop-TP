<?php
/**
 * Created by PhpStorm.
 * User: Lullabies
 * Date: 2019/12/11
 * Time: 15:51
 */

namespace app\api\controller\v1;


use think\Controller;
use app\api\service\Token as TokenService;

class BaseController extends Controller
{
    protected function checkPrimaryScope(){
        TokenService::needPrimaryScope();
    }

    protected function checkExclusionScope(){
        TokenService::needExclusionScope();
    }
}