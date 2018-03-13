<?php
//商品列表
namespace frontend\controllers;
use backend\models\Goods;
use backend\models\GoodsCategory;
use yii\data\Pagination;
use yii\web\Controller;

class ListController extends Controller{
    public function actionIndex($goods_category_id){
        $goodsCategory=GoodsCategory::findOne(['id'=>$goods_category_id]);
        switch ($goodsCategory->depth){
            //一级分类
            //二级分类
            case 0:
            case 1:
                $ids=$goodsCategory->children()->select(['id'])->andwhere(['depth'=>2])->asArray()->column();//得到的数据格式为[15,17]
                break;
            //三级分类
            case 2:
                $ids=[$goods_category_id];
        }
        $page=new Pagination();
        $page->defaultPageSize=3;
        $page->totalCount=Goods::find()->where(['in','goods_category_id',$ids])->count();
        $goods=Goods::find()->where(['in','goods_category_id',$ids])->offset($page->offset)->limit($page->limit)->all();
        return $this->render('index',['goods'=>$goods,'page'=>$page]);
    }
}
