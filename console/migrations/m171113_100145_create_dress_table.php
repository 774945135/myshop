<?php

use yii\db\Migration;

/**
 * Handles the creation of table `dress`.
 */
class m171113_100145_create_dress_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('address', [
            'id' => $this->primaryKey(),
            'name'=>$this->string()->comment('收货人'),
            'province'=>$this->string()->comment('城市'),
            'city'=>$this->string()->comment('区县'),
            'area'=>$this->string()->comment('乡镇'),
            'address'=>$this->string()->comment('详细地址'),
            'phone'=>$this->string()->comment('手机号码'),
            'default'=>$this->string()->comment('默认地址'),
            'user_id'=>$this->string()->comment('登陆用户id'),

        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('dress');
    }
}
