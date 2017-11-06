<?php

use yii\db\Migration;

class m171105_054522_goods_category extends Migration
{
    public function safeUp()
    {
        $this->createTable('goods_category',[

        'id'=>$this->primaryKey(),
            'tree'=>$this->integer()->comment('树id'),
            'lft'=>$this->integer()->comment('左值'),
            'rgt'=>$this->integer()->comment('右值'),
            'depth'=>$this->integer()->comment('层级'),
            'name'=>$this->string(50)->comment('名称'),
            'parent_id'=>$this->integer()->comment('上级分类id'),
            'intro'=>$this->text()->comment('简介'),
        ]);
    }

    public function safeDown()
    {
        echo "m171105_054522_goods_category cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171105_054522_goods_category cannot be reverted.\n";

        return false;
    }
    */
}
