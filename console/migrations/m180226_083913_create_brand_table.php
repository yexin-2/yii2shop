<?php

use yii\db\Migration;

/**
 * Handles the creation of table `brand`.
 */
class m180226_083913_create_brand_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('brand', [
            'id' => $this->primaryKey(),
            'name'=>$this->string(50)->notNull()->comment('名称'),
            'intro'=>$this->text()->comment('简介'),
            'logo'=>$this->string(255)->notNull()->comment('LOGO图片'),
            'sort'=>$this->integer(11)->notNull()->comment('排序'),
            'is_deleted'=>$this->integer(1)->notNull()->comment('状态(0正常 1删除)'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('brand');
    }
}
