<?php
namespace backend\models;

use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

class Brand extends ActiveRecord
{
    public $imgFile;

    public static function getBrand_id(){
        return ArrayHelper::map(self::find()->where(['status'=>1])->asArray()->all(),'id','name');
    }

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
          [['name','intro','sort','status','logo'],'required'],

        ];
    }
}