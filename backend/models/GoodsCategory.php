<?php
/**
 * Created by PhpStorm.
 * User: Dell
 * Date: 2017/11/5
 * Time: 16:29
 */

namespace backend\models;

use creocoder\nestedsets\NestedSetsBehavior;
use yii\db\ActiveRecord;

class GoodsCategory extends ActiveRecord
{
    public function attributeLabels()
    {
        return [
            'name'=>'分类名称',
            'parent_id'=>'上级分类',
            'intro'=>'简介',
        ];
    }


    public function rules()
    {
        return [
            [['name', 'parent_id'], 'required'],
            [['tree', 'lft', 'rgt', 'depth', 'parent_id'], 'integer'],
            [['intro'], 'string'],
        ];
    }

    public function behaviors() {
        return [
            'tree' => [
                'class' => NestedSetsBehavior::className(),
                 'treeAttribute' => 'tree',
                // 'leftAttribute' => 'lft',
                // 'rightAttribute' => 'rgt',
                // 'depthAttribute' => 'depth',
            ],
        ];
    }

    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    public static function find()
    {
        return new GoodsCategoryQuery(get_called_class());
    }

    //获取Ztree需要的数据
    public static function getZtreeNodes(){
        return self::find()->select(['id','name','parent_id'])->asArray()->all();
    }
}