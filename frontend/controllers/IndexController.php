<?php
/**
 * Created by PhpStorm.
 * User: John
 * Date: 2018/3/11
 * Time: 15:54
 */
//首页
namespace frontend\controllers;


use backend\models\GoodsCategory;
use yii\web\Controller;

class IndexController extends Controller
{
    public function actionIndex(){
        $goods_categorys=GoodsCategory::find()->where(['depth'=>0])->all();
        return $this->render('index',['goods_categorys'=>$goods_categorys]);
    }
}