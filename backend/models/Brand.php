<?php
namespace backend\models;

use yii\db\ActiveRecord;

class Brand extends ActiveRecord
{
    public $imgFile;

    public function attributeLabels()
    {
        return [
          'name'=>'品牌名称',
            'intro'=>'简介',
            'imgFile'=>'LOGO',
            'sort'=>'排序',
            'status'=>'状态'
            ];
    }

    public function rules()
    {
        return [
          [['name','intro','sort','status'],'required'],
          ['imgFile','file','extensions'=>['png','jpg','gif'],'skipOnEmpty'=>false]
        ];
    }
}