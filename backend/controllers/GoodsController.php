<?php

namespace backend\controllers;


use backend\models\GoodsCategory;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\Request;

class GoodsController extends Controller
{
   // public $enableCsrfValidation = false;
    //添加商品分类
    public function actionCategoryAdd(){

        $model = new GoodsCategory();
        $request = new Request();
        $model->parent_id =0;
        if($request->isPost){
            //接收表单数据
            $model->load($request->post());
            //验证
            if($model->validate()){
                //保存
                if($model->parent_id == 0){
                    //添加根节点
                    $model->makeRoot();
                    //跳转
                    \yii::$app->session->setFlash('success','添加根节点成功');
                    return $this->redirect('category-index');
                }else{
                    //添加子节点
                    $parent = GoodsCategory::findOne(['id'=>$model->parent_id]);
                    $model->prependTo($parent);
                }
                //跳转
                \yii::$app->session->setFlash('success','添加子节点成功');
                return $this->redirect('category-index');
            }

        }
        //显示表单
        return $this->render('category-add',['model'=>$model]);

    }

    //查看商品分类
    public function actionCategoryIndex(){
        //查询数据
        $models = GoodsCategory::find()->all();
        //展示页面
        return $this->render('category-index',['models'=>$models]);

    }

    //修改商品分类
    public function actionCategoryEdit($id){
        //根据id查询数据
        $model = GoodsCategory::findOne($id);
        $request = new Request();
        if($request->isPost){
            //接收表单数据
            $model->load($request->post());
            //验证
            if($model->validate()){
                //保存
                if($model->parent_id == 0){
                    //添加根节点
                    $model->makeRoot();
                    //跳转
                    \yii::$app->session->setFlash('success','修改根节点成功');
                    return $this->redirect('category-index');

                }else{
                    //添加子节点
                    $parent = GoodsCategory::findOne(['id'=>$model->parent_id]);
                    $model->prependTo($parent);
                }
                //跳转
                \yii::$app->session->setFlash('success','修改子节点成功');
                return $this->redirect('category-index');
            }

        }
        //展示表单
        return $this->render('category-add',['model'=>$model]);
    }

    //删除商品分类
    public function actionCategoryDel(){
        //根据id查询数据
        $request = new Request();
        $id = $request->post('id');
        if($id){
            $model = GoodsCategory::findOne(['id'=>$id]);
            //判断是否有子节点
            $child  = GoodsCategory::find()->where(['parent_id'=>$id])->all();
            if($child){
                //有子节点保留
                return '不能删除有子节点的节点,请删除所有子节点后重试!';
            }else {
                //没有子节点删除
                $model->delete();
                return 'success';
            }
        }else{
            return '节点不能存在或已经被删除';
        }

    }
}