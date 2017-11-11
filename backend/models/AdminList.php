<?php
namespace backend\models;


use yii\db\ActiveRecord;

class AdminList extends ActiveRecord
{
    public function getChildren(){
        return $this->hasMany(self::className(),['parent_id'=>'id']);
    }

    public function attributeLabels()
    {
        return [
          'name'=>'菜单名称',
            'parent_id'=>'上级菜单',
            'url'=>'路由',
            'sort'=>'排序'
        ];
    }

    public function rules()
    {
        return [
          [['name','parent_id','sort'],'required'],
            ['url','safe'],
            ['name','unique']
        ];
    }


}