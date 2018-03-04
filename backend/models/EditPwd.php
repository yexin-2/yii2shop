<?php
namespace backend\models;
use yii\base\Model;

class EditPwd extends Model{
    public $username;
    public $new_password;
    public $password;
    public function attributeLabels()
    {
        return [
            'username' => '用户名',
            'new_password'=>'新密码',
            'password'=>'确认密码',
        ];
    }
    public function rules()
    {
        return [
            [['new_password', 'password','username'], 'required'],
        ];
    }
}
