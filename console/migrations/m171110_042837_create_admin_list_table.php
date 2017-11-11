<?php

use yii\db\Migration;

/**
 * Handles the creation of table `admin_list`.
 */
class m171110_042837_create_admin_list_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('admin_list', [
            'id' => $this->primaryKey(),
            'name'=>$this->string()->comment('菜单名称'),
            'parent_id'=>$this->integer()->comment('上级菜单id'),
            'url'=>$this->string()->comment('路由'),
            'sort'=>$this->string(1)->comment('排序'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('admin_list');
    }
}
