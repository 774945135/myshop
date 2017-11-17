<?php

namespace frontend\controllers;


use backend\models\Goods;
use backend\models\GoodsCategory;
use backend\models\GoodsGallery;
use frontend\models\Address;
use frontend\models\Cart;
use frontend\models\Member;
use Prophecy\PhpDocumentor\ClassAndInterfaceTagRetriever;
use yii\data\Pagination;
use yii\web\Controller;
use yii\web\Cookie;
use yii\web\Request;

class ShopController extends Controller
{

    public $enableCsrfValidation = false;
    //商品首页
    public function actionIndex(){



        return $this->render('index');
    }

    //收货地址管理
    public function actionAddress(){


       if(!\yii::$app->user->isGuest){
           $id = \yii::$app->user->identity->id;
           //展示页面
           $models = Address::find()->where(['user_id'=>$id])->all();

           $model = new Address();
           $request = new Request();
           if($request->isPost){
               $model->load($request->post(),'');
               //var_dump($model);die;
               if($model->validate()){
                   if($model->default == 'on'){
                       $model->default = 1;
                   }else{
                       $model->default =0;
                   }
                   //保存
                   $model->user_id = $id;

                   $model->save();
                   return $this->redirect('address');
               }
           }
           return $this->render('address',['models'=>$models]);
       }
        return $this->redirect(['member/login']);

    }

    //删除收件地址
    public function actionAddressDel($id){
        //查询数据
        $model = Address::findOne(['id'=>$id]);
        //删除数据
        $model->delete();

        return $this->redirect('address');
    }

    //修改收件地址
    public function actionAddressEdit($id){
        //根据id查询数据
        $model = Address::findOne(['id'=>$id]);
        $request = new Request();
        if($request->isPost){
            $model->load($request->post(),'');
            //var_dump($model);die;
            if($model->validate()){
                if($model->default == 'on'){
                    $model->default = 1;
                }else{
                    $model->default =0;
                }

                $model->save();
                return $this->redirect('address');
            }
        }


        return $this->render('address-edit',['model'=>$model]);
    }

    //用户信息
    public function actionUser(){
        //根据登陆用户id查询用户信息

       if(!\yii::$app->user->isGuest){
           $id = \yii::$app->user->identity->id;
           $model = Member::findOne(['id'=>$id]);
           //展示页面
           return $this->render('user',['model'=>$model]);
       }
        return $this->redirect(['member/login']);

    }

    //商品展示页面
    public function actionList($goods_category_id){
        //商品分类  一级  二级  三级
        $goods_category = GoodsCategory::findOne(['id'=>$goods_category_id]);
        //三级分类
        if($goods_category->depth == 2){
            $query = Goods::find()->where(['goods_category_id'=>$goods_category_id]);

        }else{
            //二级分类
            //获取二级分类下面的所有三级分类
            //根据三级分类id
            $ids = $goods_category->children()->andWhere(['depth'=>2])->column();

            $query = Goods::find()->where(['in','goods_category_id',$ids]);

        }

        $pager = new Pagination();
        $pager->totalCount = $query->count();
        $pager->pageSize = 20;

        $models = $query->limit($pager->limit)->offset($pager->offset)->all();
        return $this->render('list',['models'=>$models,'pager'=>$pager]);
    }

    //商品详情页面
    public function actionGoods($id){
        //查询数据
        $model = Goods::findOne(['id'=>$id]);
        $path = GoodsGallery::find()->where(['goods_id'=>$id])->all();
        //var_dump($path);die;
        //展示页面
        return $this->render('goods',['model'=>$model,'path'=>$path]);
    }

    //添加到购物车
    public function actionAddCart($amount,$goods_id){
        $model = new Cart();
        $request = new Request();
        if($request->isGet){
            $model->load($request->get(),'');
            //var_dump($request->post());die;
            //验证
            if($model->validate()){
                //判断有没有登陆
                if(\yii::$app->user->isGuest){
                    //未登录保存到cookie
                    $cookies = \yii::$app->request->cookies;
                    //判断是否已经存在了cart
                    $carts = $cookies->getValue('carts');
                    if($carts){
                        //已经存在$cart将数据反序列化后取出
                        $carts = unserialize($carts);
                    }else {
                        //$cart = [$goods_id=>$amount] 不存在就创建一个数组
                        $carts = [];
                    }

                    //检测$carts是否存在$goods_id的键名
                    if(array_key_exists($goods_id,$carts)){
                        $carts[$goods_id] += $amount;
                    }else{
                        $carts[$goods_id] = $amount;
                    }

                    $cookies = \yii::$app->response->cookies;
                    $cookie = new Cookie();
                    $cookie->name = 'carts';
                    //$cart是数组不能直接存放 需要序列化再存放
                    $cookie->value = serialize($carts);
                    $cookies->add($cookie);

                }else{
                    //登陆保存数据库
                    $model->member_id = \yii::$app->user->identity->id;
                    if($cart = Cart::findOne(['member_id'=>$model->member_id,'goods_id'=>$model->goods_id])){
                        $cart->amount += $model->amount;
                        $cart->save();
                    }else{
                        $model->save();
                    }
                }
                return $this->redirect('flow1');
            }
        }

    }

    //购物车
    public function actionFlow1(){
        //展示页面

        if(\yii::$app->user->isGuest){
            //取出cookie中的$carts
            $cookies = \yii::$app->request->cookies;
            $carts = $cookies->getValue('carts');
            if($carts){
                //将取出来的值反序列化
                $carts = unserialize($carts);
            }else{
                //没有就创建一个空数组
                $carts = [];
            }
            $models = Goods::find()->where(['in','id',array_keys($carts)])->all();
        }else{
            $member_id = \yii::$app->user->identity->id;
            $carts = Cart::find()->where(['member_id'=>$member_id])->all();
            foreach ($carts as $cart){
                $carts[$cart->goods_id] = $cart->amount;
            }
            $models = Goods::find()->where(['in','id',array_keys($carts)])->all();
        }
        $money = 0;
        foreach ($models as $model){
            $money += $model->shop_price*$carts[$model->id];
        }
        return $this->render('flow1',['models'=>$models,'carts'=>$carts,'money'=>$money]);
    }

    //删除购物车商品
    public function actionDelFlow1($id){
        if(\yii::$app->user->isGuest){
            $cookies = \yii::$app->request->cookies;
            $carts = $cookies->getValue('carts');

                //反序列化
                $carts = unserialize($carts);
                unset($carts[$id]);



            $cookies = \yii::$app->response->cookies;
            $cookie = new Cookie();
            $cookie->name = 'carts';
            $cookie->value = serialize($carts);
            $cookies->add($cookie);


        }else{
            $model = Cart::findOne(['goods_id'=>$id]);
            $model->delete();
        }
        return $this->redirect('flow1');
    }

    //ajax修改购物车商品数量
    public function actionChange(){
        $goods_id = \Yii::$app->request->post('goods_id');
        $amount = \Yii::$app->request->post('amount');
        if($amount<1){
            $amount=1;
        }
        if(\Yii::$app->user->isGuest){
            //取出cookie中的购物车
            $cookies = \Yii::$app->request->cookies;
            $carts = $cookies->getValue('carts');
            if($carts){
                $carts = unserialize($carts);//$carts = ['1'=>'3','2'=>'2'];
            }else{
                $carts = [];
            }
            //修改购物车商品数量
            $carts[$goods_id] = $amount;
            //保存cookie
            $cookies = \Yii::$app->response->cookies;
            $cookie = new Cookie();
            $cookie->name = 'carts';
            $cookie->value = serialize($carts);
            $cookies->add($cookie);

        }else{
            $member_id = \yii::$app->user->identity->id;
            $model = Cart::findOne(['goods_id'=>$goods_id,'member_id'=>$member_id]);
            $model->amount = $amount;
            $model->save();
        }
    }

}