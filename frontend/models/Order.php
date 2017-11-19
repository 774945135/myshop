<?php


namespace frontend\models;


use backend\models\Goods;
use yii\db\ActiveRecord;

class Order extends ActiveRecord
{

    public $address_id;
    public static $delivery=[
        1=>[1,'速达','20','方便快捷'],
        2=>[2,'Ems','30','想去那就去那'],
        3=>[3,'乌龟','10','坚如磐石'],
    ];
    public static $payment=[
        1=>[1,'货到付款','送货上门后再收款，支持现金、POS机刷卡、支票支付'],
        2=>[2,'在线支付	','即时到帐，支持绝大数银行借记卡及部分银行信用卡'],
        3=>[3,'上门自提	','	自提时付款，支持现金、POS刷卡、支票支付'],
    ];

    public function getGoods(){

    }
    public function rules()
    {
        return [
            [['address_id','delivery_id','payment_id'],'required']
        ];
    }
}