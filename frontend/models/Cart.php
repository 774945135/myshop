<?php

namespace frontend\models;


use yii\db\ActiveRecord;
use yii\helpers\Url;

class Cart extends ActiveRecord
{
    public function rules()
    {
        return [
          [['goods_id','amount'],'required'],
            //['member_id','safe']
        ];
    }



}