<?php

namespace frontend\models;


use yii\db\ActiveRecord;

class Address extends ActiveRecord
{
    public function rules()
    {
        return [
          [['name','address','phone','province','city','area'],'required'],
            ['default','safe']
        ];
    }
}