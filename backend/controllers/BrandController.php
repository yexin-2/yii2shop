<?php
//品牌
namespace backend\controllers;

use backend\models\Brand;
use yii\data\Pagination;
use yii\web\UploadedFile;

class BrandController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $query=Brand::find();
        $pager=new Pagination();
        $pager->totalCount=$query->count();
        $pager->defaultPageSize=3;
        $brands=$query->offset($pager->offset)->limit($pager->limit)->all();
        return $this->render('index',['brands'=>$brands,'pager'=>$pager]);
    }
    //添加
    public function actionAdd(){
        $model=new Brand();
        $request=\Yii::$app->request;
        if ($request->isPost){
            $model->load($request->post());
            $model->imgFile=UploadedFile::getInstance($model,'imgFile');
            if ($model->validate()){
                if ($model->imgFile){
                    $file="/upload/brand/".uniqid().".".$model->imgFile->extension;
                    if ($model->imgFile->saveAs(\Yii::getAlias("@webroot").$file,0)){
                        $model->logo=$file;
                    }
                }
                $model->is_deleted=0;
                $model->save();
                \Yii::$app->session->setFlash('success','添加成功');
                return $this->redirect(['brand/index']);
            }else{
                var_dump($model->getErrors());exit;
            }
        }
        return $this->render('add',['model'=>$model]);
    }
    //修改
    public function actionEdit($id){
        $model=Brand::findOne(['id'=>$id]);
        $request=\Yii::$app->request;
        if ($request->isPost){
            $model->load($request->post());
            $model->imgFile=UploadedFile::getInstance($model,'imgFile');
            if ($model->validate()){
                if ($model->imgFile){
                    $file="/upload/brand/".uniqid().".".$model->imgFile->extension;
                    if ($model->imgFile->saveAs(\Yii::getAlias("@webroot").$file,0)){
                        $model->logo=$file;
                    }
                }
                $model->is_deleted=0;
                $model->save();
                \Yii::$app->session->setFlash('success','修改成功');
                return $this->redirect(['brand/index']);
            }else{
                var_dump($model->getErrors());exit;
            }
        }
        return $this->render('add',['model'=>$model]);
    }
    //删除
    public function actionDelete($id){
        $model=Brand::findOne(['id'=>$id]);
        if ($model->validate()){
            $model->is_deleted=1;
            $model->save();
            \Yii::$app->session->setFlash('success','删除成功');
            return $this->redirect(['brand/index']);
        }else{
            var_dump($model->getErrors());exit;
        }

    }
}
