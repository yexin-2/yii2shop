<?php
namespace backend\filters;
use yii\base\ActionFilter;
use yii\web\HttpException;

class Rbacfilter extends ActionFilter
{
    public function beforeAction($action)
    {
        if (!\Yii::$app->user->can($action->uniqueId)){//如果不通过该路由
            if (\Yii::$app->user->isGuest){//如果是游客
                return $action->controller->redirect(\Yii::$app->user->loginUrl)->send();//跳转登录页,并且立即发送防止返回对象导致return true
            }
            throw new HttpException(403,'没有权限访问');//抛出错误
        }
        return true;
    }
}
