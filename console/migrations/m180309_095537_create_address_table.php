<?php

use yii\db\Migration;

/**
 * Handles the creation of table `address`.
 */
class m180309_095537_create_address_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('address', [
            'id' => $this->primaryKey(),
            'name'=>$this->string(50)->notNull()->comment('收货人'),
            'member_id'=>$this->integer(11)->notNull()->comment('用户id'),
            'cmbProvince'=>$this->string(25)->notNull()->comment('省'),
            'cmbCity'=>$this->string(25)->notNull()->comment('市'),
            'cmbArea'=>$this->string(25)->notNull()->comment('区'),
            'address'=>$this->string()->notNull()->comment('详细地址'),
            'tel'=>$this->char(11)->notNull()->comment('电话'),
            'status'=>$this->integer(1)->notNull()->defaultValue(0)->comment('状态（1默认，0不默认）'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('address');
    }
}
