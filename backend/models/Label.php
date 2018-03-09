<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "label".
 *
 * @property int $id
 * @property string $name 菜单名称
 * @property string $parent_id 上级菜单
 * @property string $route 路由
 * @property int $sort 排序
 */
class Label extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'label';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'parent_id', 'sort'], 'required'],
            [['sort'], 'integer'],
            [['name', 'parent_id', 'route'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '菜单名称',
            'parent_id' => '上级菜单',
            'route' => '路由',
            'sort' => '排序',
        ];
    }
    //获取权限
    public static function getPermission(){
        $arr=[""=>'请选择路由'];
        $authManager=\Yii::$app->authManager;
        $permissions=$authManager->getPermissions();
        foreach ($permissions as $val){
            $arr[$val->name]=$val->description;
        }
        return $arr;
    }
    //获取上级菜单
    public static function getParent(){
        $arr=[""=>'请选择上级菜单',0=>'顶级菜单'];
        $parent=self::find()->where(['parent_id'=>0])->all();
        foreach ($parent as $val){
            $arr[$val->id]=$val->name;
        }
        return $arr;
    }
    //获取菜单
    public static function getLabel($menuItems){
        $menu=self::find()->andWhere(['parent_id'=>0])->all();
        foreach ($menu as $val){
            $child=self::find()->where(['parent_id'=>$val->id])->all();
                $items=[];
                foreach ($child as $value){
                    //只显示有权限的二级菜单
                    if (\Yii::$app->user->can($value->route))
                    if ($value->route){
                        $items[]=['label' =>$value->name, 'url' => [$value->route]];
                    }else{
                        $items[]=['label' =>$value->name,];
                    }
                }
                //只显示有权限的一级菜单
                if ($items)
            if ($val->route){
                $menuItems[]=['label' =>$val->name, 'url' => [$val->route],
                    'items' => $items
                ];
            }else {
                $menuItems[]=['label' =>$val->name,
                    'items' => $items
                ];
            }
        }
        return $menuItems;
    }
}
