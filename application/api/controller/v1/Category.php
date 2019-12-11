<?php
/**
 * Created by PhpStorm.
 * User: lullabies
 * Date: 2019/12/3
 * Time: 23:18
 */

namespace app\api\controller\v1;


use app\lib\exception\CategoryMissException;
use think\Controller;
use app\api\model\Category as CategoryModel;

class Category extends Controller
{
    /**
     * 获取所有类目
     * @url /category/all
     */
    public function getAllCategories(){
        $categories = CategoryModel::all([], 'img');
        if($categories->isEmpty()){
            throw new CategoryMissException();
        }
        return json($categories);
    }
}