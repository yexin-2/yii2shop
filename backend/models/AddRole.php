<?php
namespace backend\models;
use yii\base\Model;

class AddRole extends Model{
    //定义场景
    const SCENARIO_ADD ='add';
    const SCENARIO_EDIT ='edit';
    public $name;
    public $description;
    public $permission;
    public function rules()
    {
        return [
            [['name'],'required'],
            [['permission'],'safe'],
            ['name','validateName','on'=>[self::SCENARIO_ADD]],
            ['name','changeName','on'=>[self::SCENARIO_EDIT]],
        ];
    }
    public function attributeLabels()
    {
        return [
            'name'=>'角色',
            'description'=>'描述',
            'permission'=>'权限',
        ];
    }
    public function validateName(){
        $authManager=\Yii::$app->authManager;
        if ($authManager->getRole($this->name)){
            $this->addError('name','角色已存在');
        }
    }
    public function changeName(){
        if ($this->name!=\Yii::$app->request->get('name')){
            $this->validateName();
        }
    }
    public static function getRoles(){
        $arr=[];
        $authManager=\Yii::$app->authManager;
        $roles=$authManager->getRoles();
        foreach ($roles as $val){
            $arr[$val->name]=$val->name;
        }
        return $arr;
    }
}
