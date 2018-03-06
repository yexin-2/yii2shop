<?php
//管理员
namespace backend\controllers;

use backend\models\Admin;
use backend\models\EditPwd;
use backend\models\LoginForm;
use backend\models\UpdatePwd;
use yii\captcha\CaptchaAction;
use yii\data\Pagination;
use yii\filters\AccessControl;

class AdminController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $query=Admin::find();
        $pager=new Pagination();
        $pager->totalCount=$query->count();
        $pager->defaultPageSize=3;
        $admins=$query->offset($pager->offset)->limit($pager->limit)->all();
        return $this->render('index',['admins'=>$admins,'pager'=>$pager]);
    }
    //添加
    public function actionAdd(){
        $model=new Admin();
        //应用场景
        $model->scenario=Admin::SCENARIO_ADD;
        $request=\Yii::$app->request;
        if ($request->isPost){
            $model->load($request->post());
            if ($model->validate()){
                $model->created_at=time();
                $model->password_hash=\Yii::$app->security->generatePasswordHash($model->password);
                //生成随机字符串
                $model->auth_key=\Yii::$app->security->generateRandomString();
                $model->save();
                \Yii::$app->session->setFlash('success','添加成功');
                return $this->redirect(['admin/index']);
            }else{
                var_dump($model->getErrors());exit;
            }
        }
        return $this->render('add',['model'=>$model]);
    }
    //修改
    public function actionEdit($id){
        $model=Admin::findOne(['id'=>$id]);
        //应用场景
        $model->scenario=Admin::SCENARIO_EDIT;
        $request=\Yii::$app->request;
        if ($request->isPost){
            $model->load($request->post());
            if ($model->validate()){
                $model->updated_at=time();
                $model->save();
                \Yii::$app->session->setFlash('success','修改成功');
                return $this->redirect(['admin/index']);
            }else{
                var_dump($model->getErrors());exit;
            }
        }
        return $this->render('add',['model'=>$model]);
    }
    //删除
    public function actionDelete($id){
        $model=Admin::findOne(['id'=>$id]);
        $model->delete();
        \Yii::$app->session->setFlash('success','删除成功');
        return $this->redirect(['admin/index']);
    }
    //登录
    public function actionLogin(){
//        //验证cookie
//        $cookies = \Yii::$app->request->cookies;
//        if ($cookies->has('id')&&$cookies->has('password_hash')){
//            $admin=Admin::findOne(['id'=>$cookies->getValue('id')]);
//            if ($admin){
//                //用户名存在
//                if ($cookies->getValue('password_hash')==$admin->password_hash){
//                    //密码正确
//                    \Yii::$app->user->login($admin);//保存登录信息
//                }
//            }
//        }
        $model=new LoginForm();
        $request=\Yii::$app->request;
        if ($request->isPost){
            $model->load($request->post());
            if ($model->validate()){
                if ($model->check_login()){
                    \Yii::$app->session->setFlash('success','登录成功');
                    return $this->redirect(['admin/index']);
                }
            }else{
                var_dump($model->getErrors());exit;
            }
        }
        return $this->render('login',['model'=>$model]);
    }
    //注销
    public function actionLogout(){
        \Yii::$app->user->logout();
        $cookies = \Yii::$app->response->cookies;
        $cookies->remove('id');
        $cookies->remove('password_hash');
        \Yii::$app->session->setFlash('success','注销成功');
        return $this->redirect(['admin/login']);
    }
    //修改密码
    public function actionUpdatePwd($id){
        $admin=Admin::findOne(['id'=>$id]);
        $model=new UpdatePwd();
        $request=\Yii::$app->request;
        if ($request->isPost){
            $model->load($request->post());
            if ($model->validate()){
                $admin->password_hash=\Yii::$app->security->generatePasswordHash($model->password_hash);
                $admin->save();
                \Yii::$app->session->setFlash('success','重置成功');
                return $this->redirect(['admin/index']);
            }else{
                var_dump($model->getErrors());exit;
            }
        }
        return $this->render('updatePwd',['model'=>$model]);
    }
    //验证码
    public function actions()
    {
        return [
            'captcha'=>[
                'class'=>CaptchaAction::className(),
                'minLength'=>3,
                'maxLength'=>4,
            ],
        ];
    }
    //修改自己的密码
    public function actionEditPwd(){
        if (\Yii::$app->user->id){
            $admin=Admin::findOne(['id'=>\Yii::$app->user->id]);
            $model=new EditPwd();
            $model->username=$admin->username;
            $request=\Yii::$app->request;
            if ($request->isPost){
                $model->load($request->post());
                if ($model->validate()){
                    if ($model->new_password==$model->password){
                        $admin->password_hash=\Yii::$app->security->generatePasswordHash($model->new_password);
                        $admin->save();
                        \Yii::$app->session->setFlash('success','修改成功');
                        return $this->redirect(['admin/logout']);
                    }else{
                        \Yii::$app->session->setFlash('danger','两次密码不一致');
                    }
                }
            }
            return $this->render('editPwd',['model'=>$model]);
        }
        return $this->redirect(['admin/login']);
    }
    //验证是否有cookie
    public function actionCookie(){
        $cookies = \Yii::$app->request->cookies;
        if ($cookies->has('id')&&$cookies->has('password_hash')){
            $admin=Admin::findOne(['id'=>$cookies->getValue('id')]);
            if ($admin){
                //用户名存在
                if ($cookies->getValue('password_hash')==$admin->password_hash){
                    //密码正确
                    \Yii::$app->user->login($admin);//保存登录信息
                }
            }
        }
    }
    //ajax删除
    public function actionAjaxDel($id){
        $model=Admin::findOne(['id'=>$id]);
        if ($model!=null){
            $model->delete();
            return json_encode('yes');
        }else{
            return json_encode('no');
        }
    }
    //配置过滤器
    public function behaviors()
    {
        return [
            'filter'=>[
                'class'=>AccessControl::className(),
                'only'=>['edit','delete'],//加入控制
                'rules'=>[
                    //登录允许访问
                    [
                        'allow'=>true,//是否允许
                        'actions'=>['edit','delete'],//对谁操作
                        'roles'=>['@']//是否认证
                    ],
                    //都允许访问
                    [
                        'allow'=>true,//是否允许
                        'actions'=>[''],//对谁操作
                        'roles'=>['@','?']//是否认证
                    ],
                ]
            ]
        ];
    }
}
