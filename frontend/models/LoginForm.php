<?php
/**
 * Created by PhpStorm.
 * User: Dell
 * Date: 2017/11/12
 * Time: 14:39
 */

namespace frontend\models;


use yii\base\Model;

class LoginForm extends Model
{
    public $username;
    public $password;
    public $cookie;

    public function rules()
    {
        return [
          [['username','password'],'required'],
            ['cookie','safe']
        ];
    }
}