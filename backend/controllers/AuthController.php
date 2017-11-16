<?php

namespace backend\controllers;


use backend\filters\RbacFilter;
use backend\models\AdminList;
use backend\models\AuthItem;
use backend\models\PermForm;
use backend\models\RoleForm;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Request;

class AuthController extends Controller
{
    //添加权限
    public function actionPermAdd(){
        $auth = \yii::$app->authManager;

        $model = new PermForm();
        //设置当前场景
        $model->scenario = PermForm::SCENARIO_Add;

        $request = new Request();
        if($request->isPost){
            //接收表单数据
            $model->load($request->post());
            //验证
            //var_dump($model->validate($model->add()));die;
            if($model->validate() && $model->add()){
                //跳转
                \yii::$app->session->setFlash('success','添加成功');
                return $this->redirect('perm-index');
            }

        }


        //展示添加表单
        return $this->render('perm-add',['model'=>$model]);

    }

    //限权展示
    public function actionPermIndex(){
        //查询数据
        $auth = \yii::$app->authManager;
        $models = $auth->getPermissions();
        //展示页面
        return $this->render('perm-index',['models'=>$models]);

    }

    //删除权限
    public function actionPermDel(){
        $auth = \yii::$app->authManager;
        $request = new Request();
        $name = $request->post('name');
        if($name){
            //获取限权对象
            $model =  $auth->getPermission($name);
            //$parent = $auth->getRoles();

            //删除限权

                $auth->remove($model);
                //返回成功
                return 'success';


        }else{
            //失败返回错误信息
            return '限权已删除或者不存在';
        }
    }

    //修改限权
    public function actionPermEdit($name){
        $auth = \Yii::$app->authManager;
        //获取权限
        $permission =  $auth->getPermission($name);
        //如果权限不存在,提示
        if($permission == null){
            //404错误
            throw new NotFoundHttpException('权限不存在');
        }

        $model = new PermForm();
        //设置场景 , SCENARIO_Edit
        $model->scenario = PermForm::SCENARIO_Edit;
        //给oldname赋值到model中验证试验
        $model->oldName = $permission->name;
        $model->name = $permission->name;
        $model->description = $permission->description;
        $request = \Yii::$app->request;
        if($request->isPost){
            $model->load($request->post());
            //验证和调用update方法修改保存
            if($model->validate()){

                if ($model->update($name)){

                    \Yii::$app->session->setFlash('success','修改成功');
                    $this->redirect('perm-index');
                }

            }
        }
        return $this->render('perm-add',['model'=>$model]);
    }

    //添加角色
    public function actionRoleAdd(){
        $auth = \Yii::$app->authManager;
        $model= new RoleForm();
        //设置场景 , 当前场景是SCENARIO_Add场景
        $model->scenario = PermForm::SCENARIO_Add;
        $request = \Yii::$app->request;
        if ($request->isPost) {
            $model->load($request->post());
            if ($model->validate()) {
                //创建角色
                $role = $auth->createRole($model->name);
                $role->description = $model->description;

                $auth->add($role);//角色添加到数据表
                if ($model->permissions){
                    foreach ($model->permissions as $permissionName) {
                        $permission = $auth->getPermission($permissionName);//根据权限的名称获取权限对象
                        //给角色分配权限
                        $auth->addChild($role, $permission);
                    }
                }

                \Yii::$app->session->setFlash('success', '添加成功');
                return $this->redirect('role-index');
            }


        }

        $permissions = $auth->getPermissions();
        //var_dump($permissions);exit;
        $permissions = ArrayHelper::map($permissions, 'name', 'description');
        return $this->render('role-add', ['model' => $model, 'permissions' => $permissions]);
    }

    //展示角色
    public function actionRoleIndex(){
        //获取所有角色
        $auth = \yii::$app->authManager;

        $models  = $auth->getRoles();
        //展示页面
        return $this->render('role-index',['models'=>$models]);


    }

    //删除角色
    public function actionRoleDel(){
        //根据名称查询角色
        $auth = \yii::$app->authManager;
        $request = new Request();
        if($request->isPost){
            $name = $request->post('name');
            //删除角色以及与该角色对应的限权关系
            $model = $auth->getRole($name);
            $auth->remove($model);
            return 'success';
        }else{
            return '角色已被删除或者不存在';
        }


    }

    //修改角色
    public function actionRoleEdit($name){
        $auth = \Yii::$app->authManager;
        $request = \Yii::$app->request;
        $model = new RoleForm();
        //得到权限数据
        $permissions = $auth->getPermissions();
        $permissions = ArrayHelper::map($permissions,'name','description');
        //得到角色数据
        $role = $auth->getRole($name);
        //如果权限不存在,提示
        if($role == null){
            //404错误
            throw new NotFoundHttpException('权限不存在');
        }

        //设置场景 , SCENARIO_Edit
        $model->scenario = PermForm::SCENARIO_Edit;
        //给oldname赋值到model中验证试验
        $model->oldName = $role->name;
        $model->name = $role->name;
        $model->description = $role->description;

        //回显多选遍历为数组赋值给permissions
        if ($auth->getPermissionsByRole($name)){
            $pers = $auth->getPermissionsByRole($name);
            foreach ($pers as $v){
                $per[] = $v->name;
            }
            $model->permissions = $per;
        }

        if ($request->isPost){
            $model->load($request->post());
            if ($model->validate() && $model->update($name)){


                \Yii::$app->session->setFlash('success','修改成功');
                $this->redirect('role-index');
            }
        }
        return $this->render('role-add',['model'=>$model,'permissions'=>$permissions]);

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