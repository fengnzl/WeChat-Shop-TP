<?php
/**
 * Created by PhpStorm.
 * User: Lullabies
 * Date: 2019/12/10
 * Time: 9:51
 */

namespace app\api\controller\v1;


use app\lib\enum\ScopeEnum;
use app\lib\exception\ForbiddenException;
use app\lib\exception\SuccessMessage;
use app\lib\exception\TokenException;
use app\lib\exception\UserException;
use app\validate\AddressNew;
use app\api\service\Token as TokenService;
use app\api\model\User as UserModel;
use think\Controller;

class Address extends Controller
{
    //前置方法
    protected $beforeActionList = [
        'checkPrimaryScope'=>['only' => 'createOrUpdateAddress'],
    ];

    /**
     * 检测初级权限
     */
    protected function checkPrimaryScope(){
        $scope = TokenService::getCurrentTokenVar('scope');
        if($scope>=ScopeEnum::User){
            return true;
        }else{
            throw new ForbiddenException();
        }
    }

    /**
     * 根据token新增或者修改用户地址
     * @url /address
     */
    public function createOrUpdateAddress(){
        $validate = new AddressNew();
        $validate->goCheck();
        // 根据token获取用户uid->
        //根据uid查找用户是否存在，不存在抛出异常 ->
        //用户存在，获取提交的地址信息 ->
        //判断更新还是添加地址
        $uid = TokenService::getCurrentUid();
        $user = UserModel::get($uid);
        if(!$user){
            throw new UserException();
        }
        //对传递的数据进行过滤
        $dataArray = $validate ->getDataByRule(input('post.'));
        // 获取用户地址
        $userAddress = $user ->address;
        if(!$userAddress){
            // 通过模型关心新增数据
            $user->address()->save($dataArray);
        }else{
            //更新数据
            $user->address->save($dataArray);
        }

        return json(new SuccessMessage(),201);
    }
}