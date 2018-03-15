<?php

use yii\db\Migration;

/**
 * Handles the creation of table `order_goods`.
 */
class m180314_022742_create_order_goods_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('order_goods', [
            'id' => $this->primaryKey(),
            'order_id'=>$this->integer()->notNull()->comment('订单id'),
            'goods_id'=>$this->integer()->notNull()->comment('商品id'),
            'goods_name'=>$this->string(255)->notNull()->comment('商品名称'),
            'logo'=>$this->string(255)->comment('图片'),
            'price'=>$this->decimal()->notNull()->comment('价格'),
            'amount'=>$this->integer()->notNull()->comment('数量'),
            'total'=>$this->decimal()->comment('小计'),
        ],'ENGINE=InnoDB');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('order_goods');
    }
}
