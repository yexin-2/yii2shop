<?php
namespace backend\models;
use yii\base\Model;

class AddPermission extends Model{
    //定义场景
    const SCENARIO_ADD ='add';
    const SCENARIO_EDIT ='edit';
    public $name;
    public $description;
    public function rules()
    {
        return [
            [['name','description'],'required'],
            //设置不同的验证方法
            ['name','validateName','on'=>[self::SCENARIO_ADD]],
            ['name','changeName','on'=>[self::SCENARIO_EDIT]],
        ];
    }
    public function validateName(){
        $authManager=\Yii::$app->authManager;
        if ($authManager->getPermission($this->name)){
            $this->addError('name','路由已存在');
        }
    }
    public function changeName(){
        if ($this->name!=\Yii::$app->request->get('name')){
            $this->validateName();
        }
    }
    public function attributeLabels()
    {
        return [
            'name'=>'路由',
            'description'=>'描述',
        ];
    }
    public static function getPermission(){
        $arr=[];
        $authManager=\Yii::$app->authManager;
        $permissions=$authManager->getPermissions();
        foreach ($permissions as $val){
            $arr[$val->name]=$val->description;
        }
        return $arr;
    }
}
