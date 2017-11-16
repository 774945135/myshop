<?php
namespace backend\filters;

use yii\base\ActionFilter;
use yii\web\HttpException;
class RbacFilter extends ActionFilter{
    public function beforeAction($action)
    {
        //$action->uniqueId 当前路由
        //return \Yii::$app->user->can($action->uniqueId);

        if(!\Yii::$app->user->can($action->uniqueId)){
            //如果没有登录,则跳转到登录页面
            if(\Yii::$app->user->isGuest){
                //send()确保立刻跳转
                return $action->controller->redirect(\Yii::$app->user->loginUrl)->send();
            }else{
                throw new HttpException(403,'对不起,您没有该操作权限');
                return false;
            }

        }

        return parent::beforeAction($action);
    }
}