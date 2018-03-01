<?php
//商品分类
namespace backend\controllers;

use backend\models\GoodsCategory;
use yii\data\Pagination;
use yii\db\Query;

class GoodsCategoryController extends \yii\web\Controller
{
    //列表
    public function actionIndex()
    {
        $query=GoodsCategory::find();
        $pager=new Pagination();
        $pager->totalCount=$query->count();
        $pager->defaultPageSize=3;
        $goodsCategorys=$query->offset($pager->offset)->limit($pager->limit)->all();
        return $this->render('index',['goodsCategorys'=>$goodsCategorys,'pager'=>$pager]);
    }
    //添加
    public function actionAdd(){
        $model=new GoodsCategory();
        $request=\Yii::$app->request;
        if ($request->isPost){
            $model->load($request->post());
            if ($model->validate()){
                if ($model->parent_id){
                    //创建子孙节点
                    $parent=GoodsCategory::findOne(['id'=>$model->parent_id]);
                    $model->prependTo($parent);
                }else{
                    //创建根节点
                    $model->makeRoot();
                }
                $model->save();
                \Yii::$app->session->setFlash('success','添加成功');
                return $this->redirect(['goods-category/index']);
            }else{
                var_dump($model->getErrors());exit;
            }
        }
        $nodes=GoodsCategory::find()->select(['id','name','parent_id'])->asArray()->all();//转为数组形式提高效率
        $nodes[]=['id'=>0,'name'=>'顶级分类','parent_id'=>0];//添加顶级分类
        return $this->render('add',['model'=>$model,'nodes'=>json_encode($nodes)]);
    }
    //修改
    public function actionEdit($id){
        $model=GoodsCategory::findOne(['id'=>$id]);
        $request=\Yii::$app->request;
        if ($request->isPost){
            $model->load($request->post());
            if ($model->validate()){
                if ($model->parent_id){
                    //创建子孙节点
                    $parent=GoodsCategory::findOne(['id'=>$model->parent_id]);
                    $model->prependTo($parent);
                }else{
                    //创建根节点
                    $model->makeRoot();
                }
                $model->save();
                \Yii::$app->session->setFlash('success','修改成功');
                return $this->redirect(['goods-category/index']);
            }else{
                var_dump($model->getErrors());exit;
            }
        }
        $nodes=GoodsCategory::find()->select(['id','name','parent_id'])->asArray()->all();
        $nodes[]=['id'=>0,'name'=>'顶级分类','parent_id'=>0];
        return $this->render('add',['model'=>$model,'nodes'=>json_encode($nodes)]);
    }
    //删除
    public function actionDelete($id){
        $model=GoodsCategory::findOne(['id'=>$id]);
        if ($model->parent_id){
            $model->delete();
            \Yii::$app->session->setFlash('success','删除成功');
            return $this->redirect(['goods-category/index']);
        }else{
            \Yii::$app->session->setFlash('danger','该分类下有子分类');
            return $this->redirect(['goods-category/index']);
        }
    }
}
