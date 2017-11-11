<?php

namespace backend\controllers;



use backend\models\LoginForm;
use backend\models\PwdForm;
use backend\models\User;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\Cookie;
use yii\web\Request;

class UserController extends Controller
{
    //添加管理员
    public function actionAdd(){
        $auth = \yii::$app->authManager;
        $model = new User();
        $requset = new Request();
        //判断是不是post
        if($requset->isPost){
            //接收表单数据
            $model->load($requset->post());
            //验证
            if($model->validate()){
                //保存
                $model->password_hash = \yii::$app->security->generatePasswordHash($model->password_hash);
                $model->created_at = time();
                $model->auth_key = \yii::$app->security->generateRandomString();
                $model->save();
                //分配角色
                foreach($model->roles as $roleName){
                    $role = $auth->getRole($roleName);
                    $auth->assign($role,$model->id);

                }
                //跳转
                \Yii::$app->session->setFlash('success','添加成功');
                return $this->redirect('index');
            }

        }


        //展示表单页面
        $roles = $auth->getRoles();
        $roles = ArrayHelper::map($roles,'name','name');
        return $this->render('add',['model'=>$model,'roles'=>$roles]);
    }

    //展示管理员
    public function actionIndex(){
        //分页工具类
        $pager = new Pagination();
        $pager->totalCount = User::find()->count();
        $pager->pageSize = 3;
        //查询数据
        $models = User::find()->all();
        //展示页面
        return $this->render('index',['models'=>$models,'pager'=>$pager]);
    }

    //修改管理员
    public function actionEdit($id){
        //根据id查询数据
        $auth = \yii::$app->authManager;
        $model = User::findOne(['id'=>$id]);
        //var_dump($model);die;
        //$model->roles = [0=>2,1=>'超级'];
        $model->roles = $auth->getRolesByUser($id);


        $requset = new Request();
        if ($auth->getRolesByUser($id)){
            $pers = $auth->getRolesByUser($id);
            foreach ($pers as $v){
                $per[] = $v->name;
            }
            $model->roles = $per;
        }
        //判断是不是post
        if($requset->isPost){
            //接收表单数据
            $model->load($requset->post());
            //验证
            if($model->validate()){
                //保存
                $model->updated_at = time();
                $model->auth_key = \yii::$app->security->generateRandomString();
                $model->save();
                //删掉原来的角色
                $auth->revokeAll($id);
                //重新分配角色
                if($model->roles){
                    foreach($model->roles as $roleName){
                        $role = $auth->getRole($roleName);
                        $auth->assign($role,$id);

                    }
                }

                //跳转
                \Yii::$app->session->setFlash('success','修改成功');
                return $this->redirect('index');
            }

        }

        $roles = $auth->getRoles();
        $roles = ArrayHelper::map($roles,'name','name');
        //展示表单页面
        return $this->render('edit',['model'=>$model,'roles'=>$roles]);

    }

    //删除用户
    public function actionDel(){
        //接收id查询数据
        $auth = \yii::$app->authManager;
        $request = new Request();
        $id = $request->post('id');
        if($id){

            $model = User::findOne(['id'=>$id]);
            //删除数据
            $model->delete();
            $auth->revokeAll($id);
            return 'success';

        }else{
            return '管理员已被删除或者不存在';
        }


    }

    //管理员修改密码
    public function actionPwd(){
        //展示登陆表单
        $model = new PwdForm();
        $request = new Request();

        if($request->isPost){
            //接收表单数据
            $model->load($request->post());
            //验证
            if($model->validate()){
                //验证旧密码是否正确
                if(\yii::$app->request->getAuthPassword()){
                    //跳转
                }



            }
        }


        return $this->render('pwd',['model'=>$model]);
    }

    //登陆
    public function actionLogin(){
        //登陆表单
        $model = new LoginForm();
        
        
        /*$cookies = \Yii::$app->request->cookies;
        if(User::findOne(['auth_key'=>$cookies->getValue('key')])){
            $user = User::findOne(['auth_key'=>$cookies->getValue('key')]);
            $user->last_login_time = time();
            $user->last_login_ip = \yii::$app->request->userIP;
            $user->save();
            //跳转
            \yii::$app->user->login($user);
            return $this->redirect('index');
        }*/

            $request = new Request();
            if ($request->isPost) {
                //接收表单内容
                $model->load($request->post());
                //验证
                if ($model->validate()) {
                    //验证用户名密码
                    $user = User::findOne(['username' => $model->username]);
                    if ($user && \yii::$app->security->validatePassword($model->password, $user->password_hash)) {
                        /*if ($model->cookie == 1) {
                            \yii::$app->response->cookies->remove('key');
                            $user->auth_key = \yii::$app->security->generateRandomString();
                            $cookies = \Yii::$app->response->cookies;
                            $cookie = new Cookie();
                            $cookie->name = 'key';
                            $cookie->value = $user->auth_key;
                            $cookie->expire = time()+3600*7*24;//过期时间
                            $cookies->add($cookie);
                        }*/
                        
                        
                        //保存到cookie
                        $user->last_login_time = time();
                        $user->last_login_ip = \yii::$app->request->userIP;
                        $user->save();


                        //跳转
                        if($model->cookie == 1){
                            \yii::$app->user->login($user,3600*7*24);
                        }else{
                            \yii::$app->user->login($user);
                        }
                        
                        return $this->redirect('index');

                    } else {
                        \yii::$app->session->setFlash('success', '用户名或者密码错误');
                    }

                }

            }







        return $this->render('login',['model'=>$model]);
    }

    //注销
    public function actionLogout(){
        //注销
        \yii::$app->user->logout();
        \yii::$app->response->cookies->remove('key');

        //跳转
        \yii::$app->session->setFlash('success','注销成功');
        return $this->redirect('login');
    }
}