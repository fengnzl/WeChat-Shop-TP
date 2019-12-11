<?php
/**
 * Created by PhpStorm.
 * User: lullabies
 * Date: 2019/12/3
 * Time: 22:16
 */

namespace app\api\controller\v1;


use app\lib\exception\ThemeMissException;
use app\validate\IDCollection;
use app\validate\IDMustBePositiveInt;
use think\Controller;
use app\api\model\Theme as ThemeModel;
class Theme extends Controller
{
    /**
     * @url theme?ids=id1,id2
     * return 一组theme模型
     */
    public function getSimpleList($ids=''){
        (new IDCollection())->goCheck();
        $ids= explode(',',$ids);
        $result = ThemeModel::with(['topicImg','headImg'])
            ->select($ids);
        if(!$result){
            throw new ThemeMissException();
        }
        return json($result);
    }

    /**
     * 获取指定主题下的相关信息
     * @url theme/:id
     */
    public function getComplexOne($id){
        (new IDMustBePositiveInt())->goCheck();
        $result = ThemeModel::getThemeWithProducts($id);
        if(!$result){
            throw  new ThemeMissException();
        }
        return json($result);
    }
}