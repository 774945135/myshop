<?php

namespace backend\controllers;


use backend\models\Goods;
use backend\models\GoodsCategory;
use backend\models\GoodsDayCount;
use backend\models\GoodsGallery;
use backend\models\GoodsIntro;
use yii\bootstrap\ActiveForm;
use yii\data\Pagination;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\Request;
use yii\web\UploadedFile;

class GoodsController extends Controller
{
    public $enableCsrfValidation = false;
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
        $models = GoodsCategory::find()->orderBy(['tree'=>'ASC','lft'=>'ASC'])->all();
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
                    if($model->getOldAttribute('parent_id')==0){
                        $model->save();
                    }else{
                        //修改根节点
                        $model->makeRoot();
                    }

                    //跳转
                    \yii::$app->session->setFlash('success','修改根节点成功');
                    return $this->redirect('category-index');

                }else{
                    //修改子节点
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
            //$child  = GoodsCategory::find()->where(['parent_id'=>$id])->all();
            if($model->isLeaf()){
                //没有子节点删除
                $model->deleteWithChildren();
                return 'success';
            }else {
                //有子节点保留
                return '不能删除有子节点的节点,请删除所有子节点后重试!';
            }
        }else{
            return '节点不能存在或已经被删除';
        }

    }

    //添加商品
    public function actionGoodsAdd(){
        //实例化session
        $session = \yii::$app->session;
        //实例化goods
        $model = new Goods();
        $model->goods_category_id =0;
        //实例化goodsintro
        $intro = new GoodsIntro();
        //实例化goodsdaycount
        $day = new GoodsDayCount();
        $cate = GoodsCategory::find()->all();
        $request = new Request();
        if($request->isPost){
            //接收表单数据
            $model->load($request->post());
            $intro->load($request->post());
            //验证
            if($model->validate() && $intro->validate()){
                //保存时间
                $model->create_time = time();
                //添加货号
                if(GoodsDayCount::findOne(['day'=>date('Ymd',time())])){
                    $str = $session->get('sn');
                }else{
                    $str=1;
                }

                $model->sn = date('Ymd',time()).str_pad($str,5,"0",STR_PAD_LEFT);
                $session->set('sn',$str+1);

                //判断已经存在当天的添加记录
                if(GoodsDayCount::findOne(['day'=>date('Ymd',time())])){
                    $d = GoodsDayCount::findOne(['day'=>date('Ymd',time())]);
                    $d->count = $d->count + $model->stock;
                    //$d->count = $day->count;
                    //var_dump($d->count);die;
                    $d->save();
                }else{
                    $day->day = date('Ymd',time());
                    $day->count =$model->stock;
                    $day->save();
                }

                $model->save();
                $intro->goods_id = $model->id;
                $intro->save();

                //跳转
                \yii::$app->session->setFlash('success','添加成功');
                return $this->redirect('goods-index');
            }

        }
        //展示表单
        return $this->render('goods-add',['model'=>$model,'intro'=>$intro,'cate'=>$cate]);
    }

    //查看商品
    public function actionGoodsIndex(){
        $model = new Goods();
        $request = new Request();
        $keyword = $request->get('Goods');
        //分页工具类
        $pager = new Pagination;
        //总页数 当前页数 每页显示多少页
        $pager->pageSize = 3;
        $query = Goods::find()->where(['status'=>1])->andwhere(["like","name","{$keyword['title']}"]);
        $pager->totalCount = $query->count();
        //查询数据
        $models = $query->offset($pager->offset)->limit($pager->limit)->all();
        //显示表单
        return $this->render('goods-index',['models'=>$models,'pager'=>$pager,'model'=>$model]);

    }

    //修改商品
    public function actionGoodsEdit($id){
        //根据id查询数据
        $model = Goods::findOne($id);
        $intro = GoodsIntro::findOne($id);
        $request = new Request();
        if($request->isPost){
            //接收表单数据
            $model->load($request->post());
            $intro->load($request->post());
            //验证
            if($model->validate() && $intro->validate()){
                //保存
                    $model->save();
                    $intro->save();
                //跳转
                \yii::$app->session->setFlash('success','修改成功');
                return $this->redirect('goods-index');
            }

        }


        //显示表单
        return $this->render('goods-add',['model'=>$model,'intro'=>$intro]);
    }

    //删除商品
    public function actionGoodsDel(){
        //根据id查询数据
        $request = new Request();
        $id = $request->post('id');
        //删除数据
        if($id){
            $model = Goods::findOne(['id'=>$id]);

            $model->status = 0;
            $model->save(false);

            return 'yes';
        }else{
            return '商品不存在或者已删除';
        }
    }

    //预览商品
    public function actionGoodsView($id){
        //根据Id查询数据
        $model = Goods::findOne(['id'=>$id]);
        $intro = GoodsIntro::findOne(['goods_id'=>$id]);
        //分配到页面
        return $this->render('goods-view',['model'=>$model,'intro'=>$intro]);
    }

    //图片处理
    public function actionUploads(){
    if(\yii::$app->request->isPost){
        $imgFile = UploadedFile::getInstanceByName('file');
        if($imgFile){
            $fileName = '/uploads/'.uniqid().'.'.$imgFile->extension;
            $imgFile->saveAs(\yii::getAlias('@webroot').$fileName,0);
            return Json::encode(['url'=>$fileName]);

        }

     }
}

    //富文本
    public function actions()
    {
        return [
            'upload' => [
                'class' => 'kucha\ueditor\UEditorAction',
                'config' => [
                    //"imageUrlPrefix" => "http://www.baidu.com",//图片访问路径前缀
                    "imagePathFormat" => "/upload/image/{yyyy}{mm}{dd}/{time}{rand:6}" //上传保存路径
                ],
            ]
        ];
    }

    //相册图片展示
    public function actionPhotosIndex(){

        $request = new Request();
        $id = $request->get('id');
        $models = GoodsGallery::find()->where(['goods_id'=>$id])->all();

        //展示页面
        return $this->render('photo-index',['models'=>$models,'id'=>$id]);

    }

    //添加图片
    public function actionPhotoAdd(){
        $model = new GoodsGallery();
        $request = new Request();
        if($request->isPost){
            $model->load($request->post());
            $imgFile = UploadedFile::getInstanceByName('file');
            if($imgFile){
                $fileName = '/uploads/'.uniqid().'.'.$imgFile->extension;
                $imgFile->saveAs(\yii::getAlias('@webroot').$fileName,0);
               if($model->validate()){
                   $model->path = $fileName;
                   $model->goods_id = \yii::$app->request->get('id');
                   $model->save(false);
               }
                return Json::encode(['url'=>$fileName]);

            }
        }

    }

    //删除相册图片
    public function actionPhotoDel(){
        $request = new Request();
        $id = $request->post('id');
        if($id){
            $model = GoodsGallery::findOne($id);
            $model->delete();
            return 'success';
        }else{
            return '该图片已删除或不存在';
        }
    }


}