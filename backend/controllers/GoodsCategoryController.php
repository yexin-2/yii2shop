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
        //节点不能修改上级分类是自己或是自己的子孙节点
        $model=GoodsCategory::findOne(['id'=>$id]);
        $request=\Yii::$app->request;
        if ($request->isPost){
            $ord_parent_id=$model->parent_id;
            $model->load($request->post());
            if ($model->validate()){
                if ($model->parent_id==$model->getOldAttribute('parent_id')){
                    $model->save();
                }else{
                    if ($model->parent_id){
                        //创建子孙节点
                        $parent=GoodsCategory::findOne(['id'=>$model->parent_id]);
                        $model->prependTo($parent);
                    }else{
                        //顶级分类不能修改为顶级分类,即旧节点父id为0,新节点父id也为0
                        if ($model->getOldAttribute('parent_id')==0){//或者$ord_parent_id==0
                            $model->save();
                        }else{
                            //创建根节点
                            $model->makeRoot();
                        }
                    }
                    $model->save();
                }
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
        $child=GoodsCategory::find()->where(['parent_id'=>$id]);
        if ($child==null){
            if ($model->parent_id){
                $model->delete();
                \Yii::$app->session->setFlash('success','删除成功');
            }else{
                \Yii::$app->session->setFlash('danger','该分类为根分类,不能删除');
            }
        }else{
            //$model->deleteWithChildren();删除当前节点和子孙节点
            \Yii::$app->session->setFlash('danger','该分类下有子分类');
        }
        return $this->redirect(['goods-category/index']);
    }
    //ajax删除
    public function actionAjaxDel($id){
        $model=GoodsCategory::findOne(['id'=>$id]);
        $child=GoodsCategory::find()->where(['parent_id'=>$id]);
        if ($child==null){
            if ($model->parent_id){
                $model->delete();
                \Yii::$app->session->setFlash('success','删除成功');
                return json_encode('yes');
            }else{
                \Yii::$app->session->setFlash('danger','该分类为根分类,不能删除');
                return json_encode('no');
            }
        }else{
            //$model->deleteWithChildren();删除当前节点和子孙节点
            \Yii::$app->session->setFlash('danger','该分类下有子分类');
            return json_encode('no');
        }
    }
}
