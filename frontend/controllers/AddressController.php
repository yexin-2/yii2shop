<?php
//收货地址
namespace frontend\controllers;

use frontend\models\Address;

class AddressController extends \yii\web\Controller
{
//    public function actionIndex()
//    {
//        $address=Address::find()->where(['member_id'=>\Yii::$app->user->id])->all();
//        return $this->render('index',['address'=>$address]);
//    }
    public function actionAdd(){
        $model=new Address();
        $request=\Yii::$app->request;
        $model->member_id=\Yii::$app->user->id;
        if ($request->isPost){
            $model->load($request->post(),'');
            if ($model->validate()){
                $model->save();
                //将其他的默认地址清除
                $addr=Address::find()->where(['status'=>1])->andWhere(['!=','id',$model->id])->all();
                foreach ($addr as $v){
                    $v->status=0;
                    $v->save();
                }
                return $this->redirect(['address/index']);
            }else{
                var_dump($model->getErrors());exit;
            }
        }
        return $this->render('add');
    }
    public function actionEdit(){
        $request=\Yii::$app->request;

        $id=$request->post("id");

        $model=Address::findOne(['id'=>$id]);
        $request=\Yii::$app->request;
        $model->member_id=\Yii::$app->user->id;
        if ($request->isPost){
            $model->load($request->post(),'');

            if ($model->validate()){
                if ($model->status==="on"){
                    $model->status=1;
                }
                $model->save();
                $addr=Address::find()->where(['status'=>1])->andWhere(['!=','id',$model->id])->all();
                foreach ($addr as $v){
                    $v->status=0;
                    $v->save();
                }
                return $this->redirect(['address/index']);
            }else{
                var_dump($model->getErrors());exit;
            }
        }
        return $this->render('edit',['model'=>$model]);
    }
    public function actionDelete($id){
        $address=Address::findOne(['id'=>$id]);
        if ($address!=null){
            $address->delete();
            return json_encode("yes");
        }else{
            return json_encode("no");
        }
    }
    //设置默认地址
    public function actionSetStatus($id){
        $address=Address::findOne(['id'=>$id]);
        $addr=Address::find()->where(['status'=>1])->all();
        foreach ($addr as $v){
            $v->status=0;
            $v->save();
        }
        $address->status=1;
        $address->save();
        return $this->redirect(['address/index']);
    }
    public function actionValidateCp($cmbProvince){
        if ($cmbProvince=="请选择省份"){
            return "false";
        }
        return "true";
    }
    public function actionValidateCc($cmbCity){
        if ($cmbCity=="请选择城市"){
            return "false";
        }
        return "true";
    }
//    //验证电话号码是否重复
//    public function actionValidateTel($tel){
//        $phone=Address::findOne(['tel'=>$tel])->tel??"";
//        if ($phone==$tel){
//            return 'false';
//        }
//        return 'true';
//    }
    public function actionValidateCa($cmbArea){
        if ($cmbArea=="请选择区县"){
            return "false";
        }
        return "true";
    }
    //ajax页
    public function actionIndex(){
        $address=Address::find()->where(['member_id'=>\Yii::$app->user->id])->all();
        return $this->render('index',['address'=>$address]);
    }
    //ajax修改回显
    public function actionAjax($id){
        $address=Address::findOne(['id'=>$id])->toArray();
        return json_encode($address);
    }
}
