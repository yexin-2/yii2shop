<?php

use yii\db\Migration;

/**
 * Handles the creation of table `delivery`.
 */
class m180314_062622_create_delivery_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('delivery', [
            'delivery_id' => $this->primaryKey(),
            'delivery_name'=>$this->string()->notNull()->comment('配送方式名称'),
            'delivery_price'=>$this->float()->notNull()->comment('配送方式价格'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('delivery');
    }
}
