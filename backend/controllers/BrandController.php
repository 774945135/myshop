<?php

namespace backend\controllers;

use backend\models\Brand;
use Qiniu\Auth;
use Qiniu\Storage\UploadManager;
use yii\data\Pagination;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\Request;
use yii\web\UploadedFile;

class BrandController extends Controller
{
    public $enableCsrfValidation = false;
    //增
    public function actionAdd(){

        $model = new Brand();
        $request = new Request();
        if($request->isPost){
            //接收表单数据
            $model->load($request->post());
            //验证
            if($model->validate()){
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
        $query = Brand::find()->where(['status'=>1]);
        //总页数 当前页数 每页显示多少
        $pager->totalCount = $query->count();
        $pager->pageSize = 3;

        //查询数据
        $models = $query->offset($pager->offset)->limit($pager->limit)->all();
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

    //图片上传
    public function actionUploads(){
        //将上传文件封装成对象
        if(\yii::$app->request->isPost){
            $imgFile = UploadedFile::getInstanceByName('file');
            if($imgFile){
                $fileName = '/uploads/'.uniqid().'.'.$imgFile->extension;
                $imgFile->saveAs(\yii::getAlias('@webroot').$fileName,0);
                //=========将图片上传到七牛云============
                // 需要填写你的 Access Key 和 Secret Key
                $accessKey ="SO--6mNvKkCdczZ1dkFtR30AUUfn-JBWa1CkWpJF";
                $secretKey = "JBKCBLs_KLVfObdVRvtGzgcJDUdeisUxqKxh4z1H";
                //对象存储 空间名称
                $bucket = "myshop";
                $domain = 'oyxkuuq17.bkt.clouddn.com';

                // 构建鉴权对象
                $auth = new Auth($accessKey, $secretKey);

                // 生成上传 Token
                $token = $auth->uploadToken($bucket);

                // 要上传文件的本地路径
                $filePath = \Yii::getAlias('@webroot').$fileName;

                // 上传到七牛后保存的文件名
                $key = $fileName;

                // 初始化 UploadManager 对象并进行文件的上传。
                $uploadMgr = new UploadManager();

                // 调用 UploadManager 的 putFile 方法进行文件的上传。
                list($ret, $err) = $uploadMgr->putFile($token, $key, $filePath);
                //echo "\n====> putFile result: \n";
                if ($err !== null) {
                    //上传失败 打印错误
                    //var_dump($err);
                    return Json::encode(['error'=>$err]);
                } else {
                    //没有出错  打印上传结果
                    //var_dump($ret);
                    return Json::encode(['url' => 'http://' . $domain . '/' . $fileName]);
                }
            }
        }
    }

    public function actionText(){
        //$this->layout = false;
        return $this->renderPartial(['']);
    }
}