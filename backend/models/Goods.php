<?php
/**
 * Created by PhpStorm.
 * User: Dell
 * Date: 2017/11/6
 * Time: 11:03
 */

namespace backend\models;


use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;

class Goods extends ActiveRecord
{
    public $title;
    //连表查询
    public function getBrand(){
        return $this->hasOne(Brand::className(),['id'=>'brand_id']);
    }

    public function attributeLabels()
    {
        return [
            'name'=>'商品名称',
            'logo'=>'LOGO',
            'goods_category_id'=>'商品分类',
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
            [['name','brand_id','market_price','shop_price','stock','status','is_on_sale','sort','logo','goods_category_id'],'required'],
            [['market_price','shop_price'],'number'],
            ['stock','integer']
        ];
    }

    //搜索
    public function search($params)
    {
        $query = Goods::find();
        // $query->joinWith(['cate']);//关联文章类别表
        // $query->joinWith(['author' => function($query) { $query->from(['author' => 'users']); }]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 2,
            ],
        ]);
        // 从参数的数据中加载过滤条件，并验证
        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // 增加过滤条件来调整查询对象
        $query->andFilterWhere([
            // 'cname' => $this->cate.cname,
            'title' => $this->title,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title]);
        //$query->andFilterWhere(['like', 'cate.cname', $this->cname]) ;

        return $dataProvider;
    }
}