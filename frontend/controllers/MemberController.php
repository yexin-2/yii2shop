<?php
//用户
namespace frontend\controllers;

use frontend\aliyun\SignatureHelper;
use frontend\models\Cart;
use frontend\models\LoginForm;
use frontend\models\Member;
use yii\captcha\CaptchaAction;
use yii\web\Cookie;

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
//                var_dump($model->code);exit;
                    $model->password_hash=\Yii::$app->security->generatePasswordHash($model->password_hash);
                    $model->created_at=time();
                    $model->status=1;
                    $model->auth_key=\Yii::$app->security->generateRandomString();
                    $model->save(0);
                    return $this->redirect(['member/login']);
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
    //验证电话号码是否重复
    public function actionValidateTel($tel){
        $phone=Member::findOne(['tel'=>$tel])->tel??"";
        if ($phone==$tel){
            return 'false';
        }
        return 'true';
    }
    //验证邮箱是否重复
    public function actionValidateEmail($email){
        $em=Member::findOne(['email'=>$email])->email??"";
        if ($em==$email){
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
                    $cookies=\Yii::$app->request->cookies;
                    //如果有购物车cookie
                    if ($cookies->has('cart')){
                        $value=unserialize($cookies->getValue('cart'));
                        foreach ($value as $key=>$v){//[2=>12.3=>15]
                            $goods=Cart::findOne(['goods_id'=>$key]);
                            if ($goods&&$goods->member_id==\Yii::$app->user->id){//如果有该商品在数据库,并且是该登录的用户
                                $goods->amount+=$v;
                            }else{//没有
                                $goods=new Cart();
                                $goods->goods_id=$key;
                                $goods->amount=$v;
                                $goods->member_id=\Yii::$app->user->id;
                            }
                            $goods->save();
                        }
                        $cookie=$cookies->get('cart');
                        $cookies=\Yii::$app->response->cookies;
                        //清除保存在cookie里的购物车商品
                        $cookies->remove($cookie);
                    }
                    \Yii::$app->session->setFlash('success','登录成功');
                    return $this->redirect(['index/index']);
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
    //发送手机验证码
    public function actionSms($tel){
        $code=rand(1000,9999);
        $redis=new \Redis();
        $redis->connect('127.0.0.1');
        $redis->set('code-'.$tel,$code,5*60);
        $result=\Yii::$app->sms->setTel($tel)->setParams(['code'=>$code])->send();
        if ($result){
            return json_encode('yes');
        }else{
            return json_encode('no');
        }
    }
    //验证手机验证码
    public function actionValidateCode($tel,$code){
        $redis=new \Redis();
        $redis->connect('127.0.0.1');
        $real_code=$redis->get('code-'.$tel);
        if ($real_code==$code){
            return 'true';
        }
        return 'false';
    }
    public function actionTestSms() {
        \Yii::$app->sms->setTel('15282833102')->setParams(['code'=>rand(1000,9999)])->send();
//        $params = array ();
//
//        // *** 需用户填写部分 ***
//
//        // fixme 必填: 请参阅 https://ak-console.aliyun.com/ 取得您的AK信息
//        $accessKeyId = 'LTAIENqt8xrTlIy8';
//        $accessKeySecret = '5q5RYaFX5PXPvNmEmvd8jKS2lkhZd8';
//
//        // fixme 必填: 短信接收号码
//        $params["PhoneNumbers"] = "15282833102";
//
//        // fixme 必填: 短信签名，应严格按"签名名称"填写，请参考: https://dysms.console.aliyun.com/dysms.htm#/develop/sign
//        $params["SignName"] = "小叶茶坊";
//
//        // fixme 必填: 短信模板Code，应严格按"模板CODE"填写, 请参考: https://dysms.console.aliyun.com/dysms.htm#/develop/template
//        $params["TemplateCode"] = "SMS_126915009";
//
//        // fixme 可选: 设置模板参数, 假如模板中存在变量需要替换则为必填项
//        $params['TemplateParam'] = Array (
//            "code" => rand(1000,9999),
////            "product" => "阿里通信"
//        );
//
//        // fixme 可选: 设置发送短信流水号
////        $params['OutId'] = "12345";
//
//        // fixme 可选: 上行短信扩展码, 扩展码字段控制在7位或以下，无特殊需求用户请忽略此字段
////        $params['SmsUpExtendCode'] = "1234567";
//
//
//        // *** 需用户填写部分结束, 以下代码若无必要无需更改 ***
//        if(!empty($params["TemplateParam"]) && is_array($params["TemplateParam"])) {
//            $params["TemplateParam"] = json_encode($params["TemplateParam"], JSON_UNESCAPED_UNICODE);
//        }
//
//        // 初始化SignatureHelper实例用于设置参数，签名以及发送请求
//        $helper = new SignatureHelper();
//
//        // 此处可能会抛出异常，注意catch
//        $content = $helper->request(
//            $accessKeyId,
//            $accessKeySecret,
//            "dysmsapi.aliyuncs.com",
//            array_merge($params, array(
//                "RegionId" => "cn-hangzhou",
//                "Action" => "SendSms",
//                "Version" => "2017-05-25",
//            ))
//        );
//
//        return $content;
    }
    //退出
    public function actionLogout(){
        \Yii::$app->user->logout();
        return $this->redirect(['member/login']);
    }
}
