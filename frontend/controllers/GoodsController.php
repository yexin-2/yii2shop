<?php
/**
 * Created by PhpStorm.
 * User: John
 * Date: 2018/3/11
 * Time: 18:52
 */

namespace frontend\controllers;


use backend\models\Goods;
use backend\models\GoodsGallery;
use backend\models\GoodsIntro;
use frontend\models\Cart;
use yii\web\Controller;
use yii\web\Cookie;

class GoodsController extends Controller
{
    public function actionIndex($id){
        $goods=Goods::findOne(['id'=>$id]);
        $pic=GoodsGallery::find()->where(['goods_id'=>$id])->all();
        $count=GoodsGallery::find()->where(['goods_id'=>$id])->count();
        $into=GoodsIntro::findOne(['goods_id'=>$id]);
        return $this->render('index',['goods'=>$goods,'pic'=>$pic,'into'=>$into,'count'=>$count]);
    }
    //访问量
    public function actionAjax($id){
        $model=Goods::findOne(['id'=>$id]);
        $model->view_times=$model->view_times+1;
        $model->save();
    }
    //加入购物车
    public function actionAddCart($goods_id,$amount){
        if (\Yii::$app->user->isGuest){
            //未登录状态
            //存储格式：[$goods_id=>$amount,$goods_id2=>$amount2]
            //如果存在其他商品
            $cookies=\Yii::$app->request->cookies;
            $value=$cookies->getValue('cart');
            if ($value){
                $value=unserialize($value);
            }else{
                $value=[];
            }
            //如果有该商品
            if (array_key_exists($goods_id,$value)){//如果数组中存在该键
                $value[$goods_id]+=$amount;//累加
            }else{
                $value[$goods_id]=$amount;
            }
            $cookie=new Cookie();
            $cookie->name='cart';
            $cookie->value=serialize($value);//必须传入字符串，所以要先序列化
            $cookie->expire=time()+24*3600;
            $cookies=\Yii::$app->response->cookies;
            $cookies->add($cookie);
        }else{
            //登录状态
            $cart=Cart::findOne(['goods_id'=>$goods_id]);
            if ($cart&&$cart->member_id==\Yii::$app->user->id){
                $cart->amount+=$amount;
                $cart->save();
            }else{
                $model=new Cart();
                $model->amount=$amount;
                $model->member_id=\Yii::$app->user->id;
                $model->goods_id=$goods_id;
                $model->save();
            }
        }
        return $this->render('addCart');
    }
    //购物车列表
    public function actionCart(){
        if (\Yii::$app->user->isGuest){
            //未登录状态
            $cookies=\Yii::$app->request->cookies;
            $value=unserialize($cookies->getValue('cart'));
            return $this->render('cart',['value'=>$value]);
        }else{
            //登录状态
            $model=Cart::find()->where(['member_id'=>\Yii::$app->user->id])->all();
            $value=[];
            foreach ($model as $val){
                $value[$val->goods_id]=$val->amount;
            }
            return $this->render('cart',['value'=>$value]);
        }
    }
    //ajax操作购物车
    public function actionAjaxCart($goods_id,$amount){
        if (\Yii::$app->user->isGuest){
            //如果存在其他商品
            $cookies=\Yii::$app->request->cookies;
            $value=$cookies->getValue('cart');
            if ($value){
                $value=unserialize($value);
            }else{
                $value=[];
            }
            if ($amount){
                //数量不为0，修改
                $value[$goods_id]=$amount;
            }else{
                //数量为0，删除
                unset($value[$goods_id]);
            }
            $cookie=new Cookie();
            $cookie->name='cart';
            $cookie->value=serialize($value);//必须传入字符串，所以要先序列化
            $cookie->expire=time()+24*3600;
            $cookies=\Yii::$app->response->cookies;
            $cookies->add($cookie);
        }else{
            $cart=Cart::findOne(['goods_id'=>$goods_id]);
            if ($amount){
                //数量不为0，修改
                $cart->amount=$amount;
                $cart->save();
            }else{
                //数量为0，删除
                $cart->delete();
            }
        }
    }
}