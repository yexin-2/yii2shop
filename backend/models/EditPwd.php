<?php
namespace backend\models;
use yii\base\Model;

class EditPwd extends Model{
    public $username;
    public $ordPassword;
    public $new_password;
    public $password;
    public function attributeLabels()
    {
        return [
            'username' => '用户名',
            'new_password'=>'新密码',
            'password'=>'确认密码',
            'ordPassword'=>'旧密码',
        ];
    }
    public function rules()
    {
        return [
            [['new_password', 'password','username','ordPassword'], 'required'],
            //自定义验证规则
            ['ordPassword','validateOrdpassword'],
        ];
    }
    public function validateOrdpassword(){
        $result=\Yii::$app->security->validatePassword($this->ordPassword,\Yii::$app->user->identity->password_hash);
        //只处理错误的情况
        if ($result==false){
            $this->addError('ordPassword','旧密码输入错误');
        }
    }
}
