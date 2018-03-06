<?php
//文章分类
namespace backend\controllers;

use backend\models\ArticleCategory;
use yii\data\Pagination;

class ArticleCategoryController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $query=ArticleCategory::find()->where(['is_deleted'=>0]);
        $pager=new Pagination();
        $pager->totalCount=$query->count();
        $pager->defaultPageSize=3;
        $articleCategorys=$query->offset($pager->offset)->limit($pager->limit)->all();
        return $this->render('index',['articleCategorys'=>$articleCategorys,'pager'=>$pager]);
    }
    //添加
    public function actionAdd(){
        $model=new ArticleCategory();
        $request=\Yii::$app->request;
        if ($request->isPost){
            $model->load($request->post());
            if ($model->validate()){
                $model->is_deleted=0;
                $model->save();
                \Yii::$app->session->setFlash('success','添加成功');
                return $this->redirect(['article-category/index']);
            }else{
                var_dump($model->getErrors());exit;
            }
        }
        return $this->render('add',['model'=>$model]);
    }
    //修改
    public function actionEdit($id){
        $model=ArticleCategory::findOne(['id'=>$id]);
        $request=\Yii::$app->request;
        if ($request->isPost){
            $model->load($request->post());
            if ($model->validate()){
                $model->is_deleted=0;
                $model->save();
                \Yii::$app->session->setFlash('success','修改成功');
                return $this->redirect(['article-category/index']);
            }else{
                var_dump($model->getErrors());exit;
            }
        }
        return $this->render('add',['model'=>$model]);
    }
    //删除
    public function actionDelete($id){
        $model=ArticleCategory::findOne(['id'=>$id]);
        if ($model->validate()){
            $model->is_deleted=1;
            $model->save();
            \Yii::$app->session->setFlash('success','删除成功');
            return $this->redirect(['article-category/index']);
        }else{
            var_dump($model->getErrors());exit;
        }

    }
    //ajax删除
    public function actionAjaxDel($id){
        $model=ArticleCategory::findOne(['id'=>$id]);
        if ($model!=null){
            $model->is_deleted=1;
            $model->save();
            return json_encode('yes');
        }else{
            return json_encode('no');
        }
    }
}
