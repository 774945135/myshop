<?php
/**
 * Created by PhpStorm.
 * User: Dell
 * Date: 2017/11/12
 * Time: 14:07
 */

namespace frontend\controllers;



use backend\models\GoodsCategory;
use frontend\components\Sms;
use frontend\models\LoginForm;
use frontend\models\Member;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\Request;



class MemberController extends Controller
{
    public $enableCsrfValidation =false;
    //注册
    public function actionAdd(){
        //展示表单
        $model = new Member();
        //接收表单数据
        $request = new Request();
        if($request->isPost){
            //提交表单数据
            $model->load($request->post(),'');
            //验证
            //var_dump($model);die();
            if($model->validate()){
                $model->password_hash = \yii::$app->security->generatePasswordHash($model->password);
                $model->created_at = time();
                //保存
                $model->save();

                //跳转
                \yii::$app->session->setFlash('success','注册成功');
                return $this->redirect('login');
            }

        }
        return $this->render('add',['model'=>$model]);
    }

    //唯一性验证
    public function actionCheckName($username){
        if(Member::findOne(['username'=>$username])){
            return 'false';
        }
        return 'true';
}

    //登陆
    public function actionLogin(){
        //展示表单
        $model = new LoginForm();


        $request = \Yii::$app->request;
        if($request->isPost){
            $model->load($request->post(),'');
            if($model->validate()){
                //var_dump($model);die();
                $member = Member::findOne(['username'=>$model->username]);
                if($member && \yii::$app->security->validatePassword($model->password,$member->password_hash)){
                //提示 跳转
                    $member->last_login_time = time();
                    $member->last_login_ip = \yii::$app->request->userIP;
                    $member->save();
                    if($model->cookie == 'on'){
                        \yii::$app->user->login($member,3600*7*24);
                    }else{
                        \yii::$app->user->login($member);
                    }
                    echo  '登陆成功';
                    return $this->redirect(Url::to(['shop/index']));
                }else{
                    echo '帐号或者密码错误';die;
                }



            }
        }
        return $this->render('login',['model'=>$model]);
    }

    //注销
    public function actionLogout(){
        //注销成功
         \yii::$app->user->logout();
         //返回登陆界面
        return $this->render('login');
    }

    //大于测试
    public function actionSms(){
        set_time_limit(0);
        header('Content-Type: text/plain; charset=utf-8');

        $response = Sms::sendSms(
            "里世界协会", // 短信签名
            "SMS_109385442", // 短信模板编号
            "18782192122", // 短信接收者
            Array(  // 短信模板中字段的值
                "code"=>"12345",
                //"product"=>"dsd"
            )
            //"123"   // 流水号,选填
        );
        echo "查询短信发送情况(queryDetails)接口返回的结果:\n";
        print_r($response);
    }

    //阿里大于短信发送
    public function actionAddSms($tel){
        //die;
        $code = rand(100000,999999);
        $response = Sms::sendSms(
            "里世界协会", // 短信签名
            "SMS_109385442", // 短信模板编号
            $tel, // 短信接收者
            Array(  // 短信模板中字段的值
                "code"=>$code,
                //"product"=>"dsd"
            )
        //"123"   // 流水号,选填
        );

        if($response->Code == 'OK'){
            //radis保存
            $redis = new \Redis();
            $redis->connect('127.0.0.1');
            $redis->set('code_'.$tel,$code,60*10);
            return 'success';
        }else{
            return '短信发送失败,请稍后重试';
        }
    }

    //将手机验证码添加到redis
    public function actionAddCode(){
        $request = new Request();
        $captcha = $request->post('captcha');
        $tel = $request->post('tel');
        $redis = new \Redis();
        $redis->connect('127.0.0.1');
        //var_dump($redis->get('code_'.$tel) == $captcha);die;
        if($redis->get('code_'.$tel) == $captcha){
            return 'true';
        }else{
            return 'false';
        }
    }
}