<?php

namespace backend\models;


use yii\base\Model;

class LoginForm extends Model
{
    public $username;
    public $password;
    public $cookie;
    public $code;//验证码


    public function attributeLabels()
    {
        return [
            'username'=>'用户名',
            'password'=>'密码',
            'cookie'=>'自动登录',
            'code'=>'验证码'
        ];
    }

    public function rules()
    {
        return [
            [['username','password'],'required'],
            ['cookie','safe'],

            //验证码
            ['code','captcha'],
        ];
    }
}