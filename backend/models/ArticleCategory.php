<?php

namespace backend\models;


use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

class ArticleCategory extends ActiveRecord
{
    public static function getArticleCategorys(){
        return ArrayHelper::map(self::find()->where(['status'=>1])->asArray()->all(),'id','name');
    }
    public function attributeLabels()
    {
        return [
            'name'=>'文章分类',
            'intro'=>'简介',
            'sort'=>'排序',
            'status'=>'状态'
        ];
    }

    public function rules()
    {
        return [
            [['name','intro','sort','status'],'required'],
        ];
    }
}