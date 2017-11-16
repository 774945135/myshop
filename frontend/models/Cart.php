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

    public static function getMoney()
    {
        $num = 0;
        $member_id = \yii::$app->user->identity->id;
        $models = self::find()->where(['member_id'=>$member_id])->all();
        foreach ($models as $model){
            $num += \backend\models\Goods::findOne(['id'=>$model->goods_id])->shop_price*$model->amount;
        }

        $html = '<td colspan="6">购物金额总计： <strong>￥ <span id="total">'.$num .'</span></strong></td>';
        return $html;
    }

    public static function getList($models){
        $html = '';
       foreach ($models as $model):
        $html .='<tr>';
        $html .= '<td class="col1"><a href=""><img src="http://www.myadmin.com.'.\backend\models\Goods::findOne(['id'=>$model->goods_id])->logo.'" alt="" /></a>  <strong><a href="">'.\backend\models\Goods::findOne(['id'=>$model->goods_id])->name.'</a></strong></td>
        <td class="col3">￥<span><a href="">'.\backend\models\Goods::findOne(['id'=>$model->goods_id])->shop_price.'</span></td>
        <td class="col4">
            <a href="javascript:;" class="reduce_num"></a>
            <input type="text" name="amount" value="'.$model->amount.'" class="amount"/>
            <a href="javascript:;" class="add_num"></a>
        </td>
        <td class="col5">￥<span><a href="">'.$money[]=$model->amount*\backend\models\Goods::findOne(['id'=>$model->goods_id])->shop_price.'</span></td>
        <td class="col6"><a href="'.Url::to(['shop/del-flow1']).'?id='.$model->id.'">删除</a></td>
    </tr>';
    endforeach;
    return $html;
    }

}