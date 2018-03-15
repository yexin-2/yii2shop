<?php
//商品
namespace backend\controllers;

use backend\filters\Rbacfilter;
use backend\models\Goods;
use backend\models\GoodsCategory;
use backend\models\GoodsDayCount;
use backend\models\GoodsGallery;
use backend\models\GoodsIntro;
use backend\models\GoodsSearch;
use yii\data\Pagination;

class GoodsController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $query=Goods::find()->where(['status'=>1]);
        $pager=new Pagination();
        $pager->totalCount=$query->count();
        $pager->defaultPageSize=3;
        $goods=$query->offset($pager->offset)->limit($pager->limit)->all();
        //分页
        $model=new GoodsSearch();
        $request=\Yii::$app->request;
        if ($request->isGet){
            $model->load($request->get());
            if ($model->validate()){
                if ($model->g_name==null&&$model->g_sn==null&&$model->min_price==null&&$model->max_price==null){
                    $query=Goods::find()->where(['status'=>1]);
                }else{
                    $query=Goods::find()->where(['status'=>1])->andFilterWhere(['and',['like','name',$model->g_name],['like','sn',$model->g_sn],['>','shop_price',$model->min_price],['<','shop_price',$model->max_price],]);//FilterWhere用于搜索,会忽略条件中的空值
                }
//                }elseif ($model->g_sn!=null){
//                    $query=Goods::find()->where(['status'=>1])->andWhere(['or',['like','sn',$model->g_sn],]);
//                }elseif ($model->min_price!=null){
//                    $query=Goods::find()->where(['status'=>1])->andWhere(['or',['>','shop_price',$model->min_price],]);
//                }elseif ($model->max_price!=null){
//                    $query=Goods::find()->where(['status'=>1])->andWhere(['or',['<','shop_price',$model->max_price],]);
//                }
                $pager=new Pagination();
                $pager->totalCount=$query->count();
                $pager->defaultPageSize=3;
                $goods=$query->offset($pager->offset)->limit($pager->limit)->all();
                return $this->render('index',['goods'=>$goods,'pager'=>$pager,'model'=>$model]);
            }else{
                var_dump($model->getErrors());exit;
            }
        }
        return $this->render('index',['goods'=>$goods,'pager'=>$pager,'model'=>$model]);
    }
    //添加
    public function actionAdd(){
        $model=new Goods();
        $model2=new GoodsIntro();
        $model3=new GoodsCategory();
        $request=\Yii::$app->request;
        if ($request->isPost){
            $model->load($request->post());
            $model2->load($request->post());
            if ($model->validate()&&$model2->validate()){
                $model->create_time=time();
                $model->view_times=0;
                $model->status=1;
                $goodsDayCount=GoodsDayCount::findOne(['day'=>date('Ymd',time())]);
                if ($goodsDayCount==null){//今天第一个商品
                    $model4=new GoodsDayCount();
                    $model4->day=date('Ymd',time());
                    $model4->count=1;
                    $model4->save();
                    $model->sn=$model4->day.str_pad($model4->count,5,"0",STR_PAD_LEFT);
                }else{
                    $goodsDayCount->day=date('Ymd',time());
                    $goodsDayCount->count++;
                    $goodsDayCount->save();
                    $model->sn=$goodsDayCount->day.str_pad($goodsDayCount->count,5,"0",STR_PAD_LEFT);
                }
                $model->save();
                $model2->goods_id=$model->id;
                $model2->save();
                \Yii::$app->session->setFlash('success','添加成功');
                return $this->redirect(['goods/index']);
            }else{
                var_dump($model->getErrors());exit;
            }
        }
        $nodes=GoodsCategory::find()->select(['id','name','parent_id'])->asArray()->all();//转为数组形式提高效率
        $nodes[]=['id'=>0,'name'=>'顶级分类','parent_id'=>0];//添加顶级分类
        return $this->render('add',['model'=>$model,'model2'=>$model2,'nodes'=>json_encode($nodes),'model3'=>$model3]);
    }
    //修改
    public function actionEdit($id){
        $model=Goods::findOne(['id'=>$id]);
        $model2=GoodsIntro::findOne(['goods_id'=>$id]);
        $goods_category_id=$model->goods_category_id;
        $model3=GoodsCategory::findOne(['id'=>$goods_category_id]);
        $request=\Yii::$app->request;
        if ($request->isPost){
            $model->load($request->post());
            $model2->load($request->post());
            if ($model->validate()&&$model2->validate()){
                $model->save();
                $model2->goods_id=$model->id;
                $model2->save();
                \Yii::$app->session->setFlash('success','修改成功');
                return $this->redirect(['goods/index']);
            }else{
                var_dump($model->getErrors());exit;
            }
        }
        $nodes=GoodsCategory::find()->select(['id','name','parent_id'])->asArray()->all();//转为数组形式提高效率
        $nodes[]=['id'=>0,'name'=>'顶级分类','parent_id'=>0];//添加顶级分类
        return $this->render('add',['model'=>$model,'model2'=>$model2,'nodes'=>json_encode($nodes),'model3'=>$model3]);
    }
    //删除
    public function actionDelete($id){
        $model=Goods::findOne(['id'=>$id]);
        $model->status=0;
        $model->save();
        \Yii::$app->session->setFlash('success','删除成功');
        return $this->redirect(['goods/index']);
    }
    //ajax删除
    public function actionAjaxDel($id){
        $model=Goods::findOne(['id'=>$id]);
        if ($model!=null){
            $model->status=0;
            $model->save();
            return json_encode('yes');
        }else{
            return json_encode('no');
        }
    }
    //查看相册
    public function actionPic($id){
        $goodsGallerys=GoodsGallery::find()->where(['goods_id'=>$id])->all();
        $model=new GoodsGallery();
        $request=\Yii::$app->request;
        if ($request->isPost){
            $model->load($request->post());
            if ($model->validate()){
                $model->goods_id=$id;
                $model->save();
                \Yii::$app->session->setFlash('success','添加成功');
                return $this->redirect(['goods/pic','id'=>$id]);
            }else{
                var_dump($model->getErrors());exit;
            }
        }
        return $this->render('show',['goodsGallerys'=>$goodsGallerys,'model'=>$model,'ord_id'=>$id]);
    }
    //删除相片
    public function actionPicDel($id,$ord_id){
        $goodsGallerys=GoodsGallery::findOne(['id'=>$id]);
        $goodsGallerys->delete();
        \Yii::$app->session->setFlash('success','删除成功');
        return $this->redirect(['goods/pic','id'=>$ord_id]);
    }
    //ajax提交
    public function actionAjax(){
        $model=new GoodsGallery();
        $model->goods_id=$_POST['goods_id'];
        $model->path=$_POST['path'];
        $model->save();
        return json_encode(['path'=>$model->path]);
    }
    //使用百度ueditor文本编辑器插件
    public function actions()
    {
        return [
            'upload' => [
                'class' => 'kucha\ueditor\UEditorAction',
                'config' => [
                    "imageUrlPrefix"  => \Yii::getAlias('@web'),//图片访问路径前缀
                ],
            ]
        ];
    }
    //配置行为
    public function behaviors()
    {
        return [
            'rbac'=>[
                'class'=>Rbacfilter::class,
                'except'=>['upload']
            ]
        ];
    }
}
