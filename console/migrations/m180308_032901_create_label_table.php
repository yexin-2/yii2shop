<?php

use yii\db\Migration;

/**
 * Handles the creation of table `label`.
 */
class m180308_032901_create_label_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('label', [
            'id' => $this->primaryKey(),
            'name'=>$this->string(50)->notNull()->comment('菜单名称'),
            'parent_id'=>$this->string(50)->notNull()->comment('上级菜单'),
            'route'=>$this->string(50)->comment('路由'),
            'sort'=>$this->integer(11)->notNull()->comment('排序'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('label');
    }
}
