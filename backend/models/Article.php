<?php

namespace backend\models;



use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

class Article extends ActiveRecord
{
    //连表查询
    public function getCategory(){
        return $this->hasOne(ArticleCategory::className(),['id'=>'article_category_id']);
    }


    public function attributeLabels()
    {
        return [
            'name'=>'文章标题',
            'article_category_id'=>'文章分类',
            'intro'=>'简介',
            'sort'=>'排序',
            'status'=>'状态'
        ];
    }

    public function rules()
    {
        return [
            [['name','intro','sort','status','article_category_id'],'required'],
        ];
    }
}