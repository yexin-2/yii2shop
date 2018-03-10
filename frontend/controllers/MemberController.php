<?php

namespace frontend\controllers;

use frontend\models\LoginForm;
use frontend\models\Member;
use yii\captcha\CaptchaAction;

class MemberController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }
    //注册
    public function actionRegist(){
        $model=new Member();
        $request=\Yii::$app->request;
        if ($request->isPost){
            $model->load($request->post(),'');
            if ($model->validate()){
                    $model->password_hash=\Yii::$app->security->generatePasswordHash($model->password_hash);
                    $model->created_at=time();
                    $model->status=1;
                    $model->auth_key=\Yii::$app->security->generateRandomString();
                    $model->save(0);
                    return $this->redirect(['member/index']);
            }else{
                var_dump($model->getErrors());exit;
            }
        }
        return $this->render('regist');
    }
    //验证用户名是否重复
    public function actionValidateName($username){
        $name=Member::findOne(['username'=>$username])->username??"";
        if ($name==$username){
            return 'false';
        }
        return 'true';
    }
    //登录
    public function actionLogin(){
        $model=new LoginForm();
        $request=\Yii::$app->request;
        if ($request->isPost){
            $model->load($request->post(),"");
            if ($model->validate()){
                if ($model->check_login()){
                    \Yii::$app->session->setFlash('success','登录成功');
                    return $this->redirect(['site/index']);
                }
            }else{
                var_dump($model->getErrors());exit;
            }
        }
        return $this->render('login');
    }
    //验证码
    public function actions()
    {
        return [
            'captcha'=>[
                'class'=>CaptchaAction::class,
                'minLength'=>3,
                'maxLength'=>4,
            ]
        ];
    }
}
