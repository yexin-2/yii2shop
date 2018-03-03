<?php

namespace backend\controllers;

use backend\models\GoodsGallery;
use yii\data\Pagination;

class GoodsGalleryController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $query=GoodsGallery::find();
        $pager=new Pagination();
        $pager->totalCount=$query->count();
        $pager->defaultPageSize=3;
        $goodsGallerys=$query->offset($pager->offset)->limit($pager->limit)->all();
        return $this->render('index',['goodsGallerys'=>$goodsGallerys,'pager'=>$pager]);
    }
    public function actionAdd(){
        $model=new GoodsGallery();
        $request=\Yii::$app->request;
        if ($request->isPost){
            $model->load($request->post());
            if ($model->validate()){
                $model->save();
                \Yii::$app->session->setFlash('success','修改成功');
                return $this->redirect(['goods-gallery/index']);
            }
        }
        return $this->render('add',['model'=>$model]);
    }
    public function actionDelete($id){
        $model=GoodsGallery::findOne(['id'=>$id]);
        $model->delete();
        \Yii::$app->session->setFlash('success','删除成功');
        return $this->redirect(['goods-gallery/index']);
    }
}
