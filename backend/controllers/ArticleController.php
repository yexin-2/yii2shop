<?php
//文章
namespace backend\controllers;

use backend\models\Article;
use backend\models\ArticleDetail;
use yii\data\Pagination;

class ArticleController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $query=Article::find()->where(['is_deleted'=>0]);
        $pager=new Pagination();
        $pager->totalCount=$query->count();
        $pager->defaultPageSize=3;
        $articles=$query->offset($pager->offset)->limit($pager->limit)->all();
        return $this->render('index',['articles'=>$articles,'pager'=>$pager]);
    }
    //添加
    public function actionAdd(){
        $model=new Article();
        $model2=new ArticleDetail();
        $request=\Yii::$app->request;
        if ($request->isPost){
            $model->load($request->post());
            $model2->load($request->post());
            if ($model->validate()&&$model2->validate()){
                $model->is_deleted=0;
                $model->create_time=time();
                $model->save();
                $model2->article_id=$model->id;///得到上次插入的id
                $model2->save();
                \Yii::$app->session->setFlash('success','添加成功');
                return $this->redirect(['article/index']);
            }else{
                var_dump($model->getErrors());exit;
            }
        }
        return $this->render('add',['model'=>$model,'model2'=>$model2]);
    }
    //修改
    public function actionEdit($id){
        $model=Article::findOne(['id'=>$id]);
        $model2=ArticleDetail::findOne(['article_id'=>$id]);
        $request=\Yii::$app->request;
        if ($request->isPost){
            $model->load($request->post());
            $model2->load($request->post());
            if ($model->validate()&&$model2->validate()){
                $model->save();
                $model2->save();
                \Yii::$app->session->setFlash('success','修改成功');
                return $this->redirect(['article/index']);
            }else{
                var_dump($model->getErrors());exit;
            }
        }
        return $this->render('add',['model'=>$model,'model2'=>$model2]);
    }
    //删除
    public function actionDelete($id){
        $model=Article::findOne(['id'=>$id]);
        if ($model->validate()){
            $model->is_deleted=1;
            $model->save();
            \Yii::$app->session->setFlash('success','删除成功');
            return $this->redirect(['article/index']);
        }else{
            var_dump($model->getErrors());exit;
        }

    }
    //查看
    public function actionLook($id){
        $model=Article::findOne(['id'=>$id]);
        $model2=ArticleDetail::findOne(['article_id'=>$id]);
        return $this->render('look',['model'=>$model,'model2'=>$model2]);
    }
    //使用百度ueditor文本编辑器插件
    public function actions()
    {
        return [
            'upload' => [
                'class' => 'kucha\ueditor\UEditorAction',
                'config' => [
                    "imageUrlPrefix"  => "http://admin.yii2shop.com",//图片访问路径前缀
                ],
            ]
        ];
    }
}
