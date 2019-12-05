<?php
/**
 * Created by PhpStorm.
 * User: lullabies
 * Date: 2019/11/27
 * Time: 23:09
 */

namespace app\api\controller\v1;


use app\api\model\Banner as BannerModel;
use app\lib\exception\BannerMissException;
use app\validate\IDMustBePositiveInt;
use think\Controller;

class Banner extends Controller
{
    /**
     * 获取指定id的banner信息
     * @url /banner/:id
     * @http GET
     * @id banner的id号
     */
    public function getBanner($id)
    {
        (new IDMustBePositiveInt())->goCheck();
        $banner = BannerModel::getBannerByID($id);
//        $banner = BannerModel::get($id);
        if(!$banner){

            throw new BannerMissException('参数错误');
        }
        return json($banner);
    }
}