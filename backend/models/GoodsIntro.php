<?php
/**
 * Created by PhpStorm.
 * User: Dell
 * Date: 2017/11/6
 * Time: 11:20
 */

namespace backend\models;


use yii\db\ActiveRecord;

class GoodsIntro extends ActiveRecord
{
    public function attributeLabels()
    {
        return [
            'content'=>'商品详细'
        ];
    }


    public function rules()
    {
        return [
            ['content','required']
        ];
    }
}