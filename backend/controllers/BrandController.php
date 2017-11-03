<?php

namespace backend\controllers;

use backend\models\Brand;
use yii\data\Pagination;
use yii\web\Controller;
use yii\web\Request;
use yii\web\UploadedFile;

class BrandController extends Controller
{
    //增
    public function actionAdd(){

        $model = new Brand();
        $request = new Request();
        if($request->isPost){
            //接收表单数据
            $model->load($request->post());
            //将上传文件变成对象
            $model->imgFile = UploadedFile::getInstance($model,'imgFile');
            //验证
            if($model->validate()){
                $file = '/uploads/'.uniqid().'.'.$model->imgFile->extension;
                $model->imgFile->saveAs(\yii::getAlias('@webroot').$file,0);
                $model->logo = $file;
                //保存
                $model->save();
                //跳转
                \yii::$app->session->setFlash('success','添加成功');
                return $this->redirect('index');
            }

        }
        //显示表单
        return $this->render('add',['model'=>$model]);
    }

    //查
    public function actionIndex(){
        //分页工具类
        $pager = new Pagination();
        //总页数 当前页数 每页显示多少
        $pager->totalCount = Brand::find()->count();
        $pager->pageSize = 3;

        //查询数据
        $models = Brand::find()->where(['status'=>1])->offset($pager->offset)->limit($pager->limit)->all();
        //展示页面
        return $this->render('index',['models'=>$models,'pager'=>$pager]);
    }

    //改
    public function actionEdit($id){
        //根据id查询数据
        $model = Brand::findOne(['id'=>$id]);
        $request = new Request();
        if($request->isPost){
            //接收表单数据
            $model->load($request->post());
            //将上传文件变成对象
            $model->imgFile = UploadedFile::getInstance($model,'imgFile');
            //验证
            if($model->validate()){
                $file = '/uploads/'.uniqid().'.'.$model->imgFile->extension;
                $model->imgFile->saveAs(\yii::getAlias('@webroot').$file,0);
                $model->logo = $file;
                //保存
                $model->save();
                //跳转
                \yii::$app->session->setFlash('success','添加成功');
                return $this->redirect('index');
            }

        }
        return $this->render('add',['model'=>$model]);
    }

    //删
    public function actionDel(){
        $request = new Request();
        $id = $request->post('id');
        if($id){
            //根据id查询数据

            $model = Brand::findOne(['id'=>$id]);
            //修改status=-1
            $model->status = -1;
            //保存
            $model->save(false);

            return 'yes';
        }else{
            return '品牌不存在或已被删除';
        }
    }

    //回收站
    public function actionReturn(){
        //查询数据
        $models = Brand::find()->where(['status'=>-1])->all();
        //展示页面
        return $this->render('del',['models'=>$models]);
    }
    //回收站还原
    public function actionRet($id){
        //根据id查询数据
        $model = Brand::findOne(['id'=>$id]);
        //修改status=-1
        $model->status = 1;
        //保存
        $model->save(false);
        //跳转
        \yii::$app->session->setFlash('success','还原成功');
        return $this->redirect('return');
    }

    //彻底删除
    public function actionDelete($id){
        //根据id查询数据
        $model = Brand::findOne(['id'=>$id]);
        //删除
        $model->delete();
        //跳转
        \yii::$app->session->setFlash('success','彻底删除成功');
        return $this->redirect('return');
    }
}