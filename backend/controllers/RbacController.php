<?php

namespace backend\controllers;

use backend\filters\Rbacfilter;
use backend\models\AddPermission;
use backend\models\AddRole;
use yii\data\Pagination;

class RbacController extends \yii\web\Controller
{
    //测试
    public function actionTest(){
        //实例化
        $authManager=\Yii::$app->authManager;
        //添加一个权限
        $permission1=$authManager->createPermission('brand/add');
        $permission1->description='添加品牌';
        $authManager->add($permission1);

        $permission2=$authManager->createPermission('brand/index');
        $permission2->description='品牌列表';
        $authManager->add($permission2);
        //添加一个角色
        $role1=$authManager->createRole('超级管理员');
        $authManager->add($role1);

        $role2=$authManager->createRole('普通管理员');
        $authManager->add($role2);
        //添加角色关联权限
        //获取角色
        $role1=$authManager->getRole('超级管理员');
        $role2=$authManager->getRole('普通管理员');
        //获取权限
        $permission1=$authManager->getPermission('brand/add');
        $permission2=$authManager->getPermission('brand/index');
        $authManager->addChild($role1,$permission1);
        $authManager->addChild($role1,$permission2);
        $authManager->addChild($role2,$permission2);
        //给用户添加角色
        $authManager->assign($role1,13);
        $authManager->assign($role2,19);
    }
    //添加权限
    public function actionAddPermission(){
        $model=new AddPermission();
        $model->scenario=AddPermission::SCENARIO_ADD;
        $request=\Yii::$app->request;
        if ($request->isPost){
            $model->load($request->post());
            if ($model->validate()){
                $authManager=\Yii::$app->authManager;
                $permission=$authManager->createPermission($model->name);
                $permission->description=$model->description;
                $authManager->add($permission);
                \Yii::$app->session->setFlash('success','添加成功');
                return $this->redirect(['rbac/index-permission']);
            }
        }
        return $this->render('addPermission',['model'=>$model]);
    }
    //展示权限
    public function actionIndexPermission(){
        $authManager=\Yii::$app->authManager;
        $permissions=$authManager->getPermissions();
        return $this->render('indexPermission',['permissions'=>$permissions]);
    }
    //修改权限
    public function actionEditPermission($name){
        $model=new AddPermission();
        $model->scenario=AddPermission::SCENARIO_EDIT;
        $model->name=$name;
        $authManager=\Yii::$app->authManager;
        $model->description=$authManager->getPermission($name)->description;
        $request=\Yii::$app->request;
        if ($request->isPost){
            $model->load($request->post());
            if ($model->validate()){
                $permission=$authManager->createPermission($model->name);
                $permission->description=$model->description;
                $authManager->update($name,$permission);
                \Yii::$app->session->setFlash('success','修改成功');
                return $this->redirect(['rbac/index-permission']);
            }
        }
        return $this->render('addPermission',['model'=>$model]);
    }
    //删除权限
    public function actionDeletePermission($name){
        $authManager=\Yii::$app->authManager;
        $permission=$authManager->getPermission($name);
        if ($permission!=null){
            $authManager->remove($permission);
            return json_encode('yes');
        }else{
            return json_encode('no');
        }
    }
    //显示角色
    public function actionIndexRole(){
        $authManager=\Yii::$app->authManager;
        $roles=$authManager->getRoles();
        return $this->render('indexRole',['roles'=>$roles]);
    }
    //添加角色
    public function actionAddRole(){
        $model=new AddRole();
        $model->scenario=AddRole::SCENARIO_ADD;
        $request=\Yii::$app->request;
        if ($request->isPost){
            $model->load($request->post());
            if ($model->validate()){
                $authManager=\Yii::$app->authManager;
                $role=$authManager->createRole($model->name);
                $role->description=$model->description;
                $authManager->add($role);
                if ($model->permission){//如果选择了权限，添加角色与权限关系
                    $role=$authManager->getRole($model->name);
                    foreach ($model->permission as $val){
                        $permission=$authManager->getPermission($val);
                        $authManager->addChild($role,$permission);
                    }
                }
                \Yii::$app->session->setFlash('success','添加成功');
                return $this->redirect(['rbac/index-role']);
            }
        }
        return $this->render('addRole',['model'=>$model]);
    }
    //修改角色
    public function actionEditRole($name){
        $model=new AddRole();
        $model->scenario=AddRole::SCENARIO_EDIT;
        $model->name=$name;
        $authManager=\Yii::$app->authManager;
        $model->description=$authManager->getRole($name)->description;
        $child=$authManager->getChildren($name);
        foreach ($child as $key=>$val){
            $model->permission[]=$key;
        }
        $request=\Yii::$app->request;
        if ($request->isPost){
            $model->load($request->post());
            if ($model->validate()){
                $role=$authManager->createRole($model->name);
                $role->description=$model->description;
                $authManager->update($name,$role);
                if ($model->permission){//如果选择了权限，添加角色与权限关系
                    $role=$authManager->getRole($model->name);
                    $authManager->removeChildren($role);
                    foreach ($model->permission as $val){
                        $permission=$authManager->getPermission($val);
                        $authManager->addChild($role,$permission);
                    }
                }else{
                    $authManager->removeChildren($role);
                }
                \Yii::$app->session->setFlash('success','修改成功');
                return $this->redirect(['rbac/index-role']);
            }
        }
        return $this->render('addRole',['model'=>$model]);
    }
    //删除角色
    public function actionDeleteRole($name){
        $authManager=\Yii::$app->authManager;
        $role=$authManager->getRole($name);
        if ($role!=null){
            $authManager->remove($role);
            return json_encode('yes');
        }else{
            return json_encode('no');
        }
    }
    //配置行为
    public function behaviors()
    {
        return [
            'rbac'=>[
                'class'=>Rbacfilter::class,
                'except'=>['']
            ]
        ];
    }
}
