<?php

namespace backend\controllers;

use backend\filters\Rbacfilter;
use backend\models\Label;

class LabelController extends \yii\web\Controller
{
    //列表
    public function actionIndex()
    {
        $labels=Label::find()->all();
        return $this->render('index',['labels'=>$labels]);
    }
    //添加
    public function actionAdd(){
        $model=new Label();
        $request=\Yii::$app->request;
        if ($request->isPost){
            $model->load($request->post());
            if ($model->validate()){
                $model->save();
                \Yii::$app->session->setFlash('success','添加成功');
                return $this->redirect(['label/index']);
            }
        }
        return $this->render('add',['model'=>$model]);
    }
    //修改
    public function actionEdit($id){
        $model=Label::findOne(['id'=>$id]);
        $request=\Yii::$app->request;
        if ($request->isPost){
            $model->load($request->post());
            if ($model->validate()){
                $model->save();
                \Yii::$app->session->setFlash('success','修改成功');
                return $this->redirect(['label/index']);
            }
        }
        return $this->render('add',['model'=>$model]);
    }
    //删除
    public function actionDelete($id){
        $model=Label::findOne(['id'=>$id]);
        if ($model->validate()){
            $model->delete();
            return json_encode('yes');
        }else{
            return json_encode('no');
        }
    }
    //配置行为
    public function behaviors()
    {
        return [
            'rbac'=>[
                'class'=>Rbacfilter::class,
                'except'=>['']
            ]
        ];
    }
}
