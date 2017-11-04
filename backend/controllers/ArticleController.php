<?php

namespace backend\controllers;


use backend\models\Article;
use backend\models\ArticleCategory;
use backend\models\ArticleDetail;
use yii\data\Pagination;
use yii\web\Controller;
use yii\web\Request;

class ArticleController extends Controller
{
    //文章分类添加
    public function actionCategoryAdd(){
        $model = new ArticleCategory();
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
                return $this->redirect('category-index');
        }

        }
        //显示表单
        return $this->render('category-add',['model'=>$model]);
    }

    //文章分类展示
    public function actionCategoryIndex(){
        //分页工具类
        $pager = new Pagination();
        $query = ArticleCategory::find()->where(['status'=>1]);
        //总页数 当前页数 每页显示多少
        $pager->totalCount = $query->count();
        $pager->pageSize = 3;

        //查询数据
        $models = $query->offset($pager->offset)->limit($pager->limit)->all();
        //展示页面
        return $this->render('category-index',['models'=>$models,'pager'=>$pager]);
    }

    //文章分类修改
    public function actionCategoryEdit($id){
        //根据id查询数据
        $model = ArticleCategory::findOne(['id'=>$id]);
        $request = new Request();
        if($request->isPost){
            //接收表单数据
            $model->load($request->post());
            //验证
            if($model->validate()){
                //保存
                $model->save();
                //跳转
                \yii::$app->session->setFlash('success','修改成功');
                return $this->redirect('category-index');
            }

        }
        return $this->render('category-add',['model'=>$model]);
    }

    //文章分类删除
    public function actionCategoryDel(){
        $request = new Request();
        $id = $request->post('id');
        if(Article::findOne(['article_category_id'=>$id])){
            //有文章选中的不能删除
            return '有文章选中该分类,请修改后删除';
        }else{
            //没有文章选中 判断id是否存在
            if($id){
                //根据id查询数据

                $model = ArticleCategory::findOne(['id'=>$id]);
                //修改status=-1
                $model->status = -1;
                //保存
                $model->save();

                return 'yes';
            }else{
                return '文章分类不存在或已被删除';
            }
        }

    }

    //文章分类回收站
    public function actionCategoryReturn(){
        //查询数据
        $models = ArticleCategory::find()->where(['status'=>-1])->all();
        //展示页面
        return $this->render('category-del',['models'=>$models]);
    }

    //文章分类还原
    public function actionCategoryRet($id){
        //根据id查询数据
        $model = ArticleCategory::findOne(['id'=>$id]);
        //修改status=-1
        $model->status = 1;
        //保存
        $model->save(false);
        //跳转
        \yii::$app->session->setFlash('success','还原成功');
        return $this->redirect('category-return');
    }

    //彻底删除文章分类
    public function actionCategoryDelete($id){
        //根据id查询数据
        $model = ArticleCategory::findOne(['id'=>$id]);
        //删除
        $model->delete();
        //跳转
        \yii::$app->session->setFlash('success','彻底删除成功');
        return $this->redirect('category-return');
    }

    //添加文章
    public function actionArticleAdd(){
        //展示表单
        $model = new Article();
        $detail = new ArticleDetail();
        $request = new Request();
        if($request->isPost){
            //接收表单数据
            $model->load($request->post());
            $detail->load($request->post());
            //验证
            if($model->validate() && $detail->validate()){
                //保存文章添加时间
                $model->create_time = time();
                $model->save();
                $id = \yii::$app->db->getLastInsertID();
                //保存文章内容对应文章id
                $detail->article_id = $id;
                $detail->save();
                //跳转
                \yii::$app->session->setFlash('success','添加成功');
                return $this->redirect('article-index');
            }

        }

        return $this->render('add',['model'=>$model,'detail'=>$detail]);
    }

    //展示文章列表
    public function actionArticleIndex(){
        //分页工具类
        $pager = new Pagination();
        $query = Article::find()->where(['status'=>1]);
        //总页数 当前页数 每页显示多少
        $pager->totalCount =$query->count();
        $pager->pageSize = 3;

        //查询数据
        $models = $query->offset($pager->offset)->limit($pager->limit)->all();
        //展示页面
        return $this->render('index',['models'=>$models,'pager'=>$pager]);
    }

    //修改文章
    public function actionArticleEdit($id){
        //根据id查询数据
        $model = Article::findOne(['id'=>$id]);
        $detail = ArticleDetail::findOne(['article_id'=>$id]);
        $request = new Request();
        if($request->isPost){
            //接收表单数据
            $model->load($request->post());
            $detail->load($request->post());
            //验证
            if($model->validate() && $detail->validate()){
                //保存文章添加时间
                $model->create_time = time();
                $model->save();
                $id = \yii::$app->db->getLastInsertID();
                //保存文章内容对应文章id
                $detail->article_id = $id;
                $detail->save();
                //跳转
                \yii::$app->session->setFlash('success','修改成功');
                return $this->redirect('article-index');
            }

        }
        //展示表单
        return $this->render('add',['model'=>$model,'detail'=>$detail]);
    }

    //删除文章
    public function actionArticleDel(){
        $request = new Request();
        $id = $request->post('id');

            if($id){
                //根据id查询数据

                $model = Article::findOne(['id'=>$id]);
                //修改status=-1
                $model->status = -1;
                //保存
                $model->save(false);

                return 'yes';
            }else{
                return '文章不存在或已被删除';
            }

    }

    //文章回收站
    public function actionArticleReturn(){
        //查询数据
        $models = Article::find()->where(['status'=>-1])->all();
        //展示页面
        return $this->render('del',['models'=>$models]);
    }

    //文章还原
    public function actionArticleRet($id){
        //根据id查询数据
        $model = Article::findOne(['id'=>$id]);
        //修改status=-1
        $model->status = 1;
        //保存
        $model->save(false);
        //跳转
        \yii::$app->session->setFlash('success','还原成功');
        return $this->redirect('article-return');
    }

    //彻底删除文章
    public function actionArticleDelete($id){
        //根据id查询数据
        $model = Article::findOne(['id'=>$id]);
        //删除
        $model->delete();
        //跳转
        \yii::$app->session->setFlash('success','彻底删除成功');
        return $this->redirect('article-return');
    }
}

