<?php
namespace frontend\controllers;
use backend\models\Goods;
use frontend\models\Address;
use frontend\models\Cart;
use frontend\models\Delivery;
use frontend\models\Order;
use frontend\models\OrderGoods;
use frontend\models\Payment;
use yii\data\Pagination;
use yii\db\Exception;
use yii\web\Controller;

class OrderController extends Controller{
    public function actionIndex(){
        if (\Yii::$app->user->isGuest){
            return $this->redirect(['member/login']);
        }else{
            $address=Address::find()->where(['member_id'=>\Yii::$app->user->id])->all();
            $cart=Cart::find()->where(['member_id'=>\Yii::$app->user->id])->all();
            return $this->render('index',['address'=>$address,'cart'=>$cart]);
        }
    }
    public function actionAdd($address_id,$deli_id,$payment_id){

        //开启事务
        $transaction=\Yii::$app->db->beginTransaction();
        try{
            //添加订单表数据
            $order=new Order();
            $address=Address::findOne(['id'=>$address_id]);
            $order->member_id=$address->member_id;
            $order->name=$address->name;
            $order->province=$address->cmbProvince;
            $order->city=$address->cmbCity;
            $order->area=$address->cmbArea;
            $order->address=$address->address;
            $order->tel=$address->tel;
            $delivery=Delivery::findOne(['delivery_id'=>$deli_id]);
            $order->delivery_id=$delivery->delivery_id;
            $order->delivery_name=$delivery->delivery_name;
            $order->delivery_price=$delivery->delivery_price;
            $payment=Payment::findOne(['payment_id'=>$payment_id]);
            $order->payment_id=$payment->payment_id;
            $order->payment_name=$payment->payment_name;

            $order->status=1;
            $order->create_time=time();
//            $cart=Cart::find()->where(['member_id'=>\Yii::$app->user->id])->all();
            $order->total=0;
            //添加订单商品详情表数据
            $cart=Cart::find()->where(['member_id'=>\Yii::$app->user->id])->all();
            foreach ($cart as $c){

                $goods=Goods::findOne(['id'=>$c->goods_id]);
                $order->total=$order->total+$c->amount*$goods->shop_price+$delivery->delivery_price;
                if ($c->amount>$goods->stock){
                    //超出库存，抛出异常
                    throw new Exception('商品库存不足');
                }
                $goods->stock-=$c->amount;
                $goods->save();

                $order->save();

                $order_goods=new OrderGoods();
                $order_goods->order_id=$order->id;
                $goods=Goods::findOne(['id'=>$c->goods_id]);
                $order_goods->goods_id=$goods->id;
                $order_goods->goods_name=$goods->name;
                $order_goods->logo=$goods->logo;
                $order_goods->price=$goods->shop_price;
                $order_goods->amount=$c->amount;
                $order_goods->total=$c->amount*$goods->shop_price;
                $order_goods->save();
                //清空购物车
                Cart::findOne(['id'=>$c->id])->delete();//或者Cart::deleteAll(['member_id'=>\Yii::$app->user->id]);
            }
            //提交事务
            $transaction->commit();
            return json_encode('yes');
        }catch (Exception $e){
            //回滚事务
            $transaction->rollBack();
            return json_encode('no');
        }
    }
    //我的订单
    public function actionMyOrder(){
        if (\Yii::$app->user->isGuest){
            return $this->redirect(['member/login']);
        }else {
            $page=new Pagination();
            $page->defaultPageSize=3;
            $page->totalCount=Order::find()->where(['member_id' => \Yii::$app->user->id])->count();
            $order = Order::find()->where(['member_id' => \Yii::$app->user->id])->offset($page->offset)->limit($page->limit)->orderBy('id desc')->all();
            $status=['已取消','待付款','待发货','待收货','完成'];
            return $this->render('my-order', ['order' => $order,'status'=>$status,'page'=>$page]);
        }
    }
    public function actionDel($status,$id){
        if ($status==0||$status==4){
            Order::findOne(['id'=>$id])->delete();
            OrderGoods::deleteAll(['order_id'=>$id]);
            return json_encode('yes');
        }else{
            $order=Order::findOne(['id'=>$id]);
            $order->status=0;
            $order->save();
            return json_encode('no');
        }
    }
    public function actionSuccess(){
        return $this->render('success');
    }
}
