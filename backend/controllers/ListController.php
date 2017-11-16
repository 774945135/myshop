<?php

namespace backend\controllers;


use backend\filters\RbacFilter;
use backend\models\AdminList;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\Request;

class ListController extends Controller
{
    //菜单添加
    public function actionAdd(){
        //展示表单
        $model = new AdminList();
        $request = new Request();
        if($request->isPost){
            //接收表单数据
            $model->load($request->post());
            //验证
            if($model->validate()){
                //保存
                $model->save();
                \yii::$app->session->setFlash('success','添加成功');
                //跳转
                return $this->redirect('index');
            }


        }
        //获取上级菜单列表
        $models = AdminList::find()->where(['parent_id'=>0])->asArray()->all();

        $models = ArrayHelper::merge([0=>['id'=>0,'name'=>'顶级分类','parent_id'=>0]],$models);
        $models = ArrayHelper::map($models,'id','name');
        /*$models_0 = [0=>['===请选择上级菜单===']];
        $models = $models_0+$models;*/
        //获取路由
        $auth = \yii::$app->authManager;
        $url = $auth->getPermissions();
        $url = ArrayHelper::map($url,'name','name');
        $url_0 = [''=>'===请选择路由==='];
        $url = $url_0+$url;

        return $this->render('add',['model'=>$model,'models'=>$models,'url'=>$url]);
    }

    //菜单展示
    public function actionIndex(){
        //分页类
        $pager = new Pagination();
        $pager->totalCount = AdminList::find()->count();
        $pager->pageSize = 3;
        //查询数据
        $models = AdminList::find()->orderBy('id asc')->all();
        //展示页面
        return $this->render('index',['models'=>$models,'pager'=>$pager]);

    }

    //菜单修改
    public function actionEdit($id){
        //根据id查询数据
        $model = AdminList::findOne(['id'=>$id]);

        $request = new Request();
        if($request->isPost){
            //接收表单数据
            $model->load($request->post());
            //验证
            if($model->validate()){
                //保存
                $model->save();
                \yii::$app->session->setFlash('success','修改成功');
                //跳转
                return $this->redirect('index');
            }


        }


        //展示表单
        //获取上级菜单列表
        $models = AdminList::find()->where(['parent_id'=>0])->asArray()->all();

        $models = ArrayHelper::merge([0=>['id'=>0,'name'=>'顶级分类','parent_id'=>0]],$models);
        $models = ArrayHelper::map($models,'id','name');

        //获取路由
        $auth = \yii::$app->authManager;
        $url = $auth->getPermissions();
        $url = ArrayHelper::map($url,'name','name');
        $url_0 = [''=>'===请选择路由==='];
        $url = $url_0+$url;
        return $this->render('add',['model'=>$model,'models'=>$models,'url'=>$url]);

    }

    //菜单删除
    public function actionDel(){
        $request = new Request();
        $id = $request->post();
        if($id){
            $model = AdminList::findOne(['id'=>$id]);
            $model->delete();
            return 'success';
        }else{
            return '菜单已被删除或不存在';
        }
    }

    public function behaviors()
    {
        return [
            'rbac'=>[
                'class'=>RbacFilter::className(),
                'except'=>['login'],
            ]
        ];

    }
}