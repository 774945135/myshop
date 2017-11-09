<?php

namespace backend\models;


use yii\base\Model;

class PwdForm extends Model
{
    public $oldpassword;
    public $newpassword;
    public $repassword;

    public function attributeLabels()
    {
        return [
          'oldpassword'=>'旧密码',
            'newpassword'=>'新密码',
            'repassword'=>'确认密码',
        ];
    }

    public function rules()
    {
        return [
          [['oldpassword','newpassword','repasswrod'],'required'],
            ['repassword', 'compare', 'compareAttribute' => 'newpassword', 'message' => '两次密码不一致']
        ];
    }
}