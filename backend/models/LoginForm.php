<?php
namespace backend\models;
use yii\base\Model;
use yii\web\Cookie;

class LoginForm extends Model{
    public $username;
    public $password_hash;
    public $codes;
    public $auto_login;
    public function rules()
    {
        return [
            [['username','password_hash'],'required'],
            [['codes'],'captcha','captchaAction'=>'admin/captcha'],
            ['auto_login','safe'],
        ];
    }
    public function attributeLabels()
    {
        return [
            'username'=>'用户名',
            'password_hash'=>'密码',
            'codes'=>'验证码',
            'auto_login'=>'记住登录'
        ];
    }
    //验证登录
    public function check_login(){
        $admin=Admin::findOne(['username'=>$this->username,'status'=>1]);
        if ($admin){
            //用户名存在
            if (\Yii::$app->security->validatePassword($this->password_hash,$admin->password_hash)){
                //密码正确
                $duration=$this->auto_login?24*3600:0;
                \Yii::$app->user->login($admin,$duration);//保存登录信息
                $admin->last_login_time=time();
                $admin->last_login_ip=$_SERVER['REMOTE_ADDR'];
                $admin->save();
//                //自动登录
//                if ($this->auto_login!=null){
//                    $cookies = \Yii::$app->response->cookies;//可写
//                    $cookie = new Cookie();
//                    $cookie->name = "id";
//                    $cookie->value = $admin->id;
//                    $cookie->expire=time()+24*3600;
//                    $cookies->add($cookie);
//                    $cookie2 = new Cookie();
//                    $cookie2->name = "password_hash";
//                    $cookie2->value = $admin->password_hash;
//                    $cookie2->expire=time()+24*3600;
//                    $cookies->add($cookie2);
//                }
                return true;
            }else{
                $this->addError('username','用户名或密码错误');//添加错误信息
            }
        }else{
            $this->addError('username','用户名或密码错误');//添加错误信息
        }
    }
}
