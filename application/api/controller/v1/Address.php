<?php
/**
 * Created by PhpStorm.
 * User: Lullabies
 * Date: 2019/12/10
 * Time: 9:51
 */

namespace app\api\controller\v1;


use app\api\model\User as UserModel;
use app\api\service\Token as TokenService;
use app\lib\exception\SuccessMessage;
use app\lib\exception\UserException;
use app\validate\AddressNew;

class Address extends BaseController
{
    //前置方法
    protected $beforeActionList = [
        'checkPrimaryScope'=>['only' => 'createOrUpdateAddress'],
    ];

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