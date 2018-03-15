<?php

use yii\db\Migration;

/**
 * Handles the creation of table `payment`.
 */
class m180314_062901_create_payment_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('payment', [
            'payment_id' => $this->primaryKey(),
            'payment_name' => $this->string()->notNull()->comment('支付方式名称'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('payment');
    }
}
