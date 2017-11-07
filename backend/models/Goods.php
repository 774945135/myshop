<?php
/**
 * Created by PhpStorm.
 * User: Dell
 * Date: 2017/11/6
 * Time: 11:03
 */

namespace backend\models;


use yii\db\ActiveRecord;

class Goods extends ActiveRecord
{
    //连表查询
    public function getBrand(){
        return $this->hasOne(Brand::className(),['id'=>'brand_id']);
    }

    public function attributeLabels()
    {
        return [
            'name'=>'商品名称',
            'logo'=>'LOGO',
            //'goods_category_id'=>'商品分类',
            'brand_id'=>'品牌分类',
            'market_price'=>'市场价格',
            'shop_price'=>'市场价格',
            'stock'=>'库存',
            'is_on_sale'=>'是否在售',
            'status'=>'是否上架',
            'sort'=>'排序',
        ];
    }

    public function rules()
    {
        return [
            [['name','brand_id','market_price','shop_price','stock','status','is_on_sale','sort','logo'],'required'],
            [['market_price','shop_price'],'number'],
            ['stock','integer']
        ];
    }
}