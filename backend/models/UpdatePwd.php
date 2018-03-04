<?php
namespace backend\models;
use yii\base\Model;

class UpdatePwd extends Model{
    public $password_hash;
    public function attributeLabels()
    {
        return [
            'password_hash'=>'密码'
        ];
    }
    public function rules()
    {
        return [
            ['password_hash','string']
        ];
    }
}
