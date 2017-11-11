<?php

namespace backend\models;


use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

class User extends ActiveRecord implements IdentityInterface
{
    public $roles;

    public function attributeLabels()
    {
        return [
            'username'=>'用户名',
            'password_hash'=>'密码',
            'email'=>'邮箱',
            'status'=>'状态',
            'roles'=>'分配角色',
        ];
    }

    public function rules()
    {
        return [
          [['username','password_hash','email','status'],'required'],
            ['email','email'],
            [['username','email'],'unique'],
            ['roles','safe']
        ];
    }

    public function getList(){
        $menuItems = [];
        //获取所有一级菜单
        $menus = AdminList::find()->where(['parent_id'=>0])->all();
        foreach ($menus as $menu){

            $items = [];
            //遍历该一级菜单的子菜单
            foreach ($menu->children as $child){
                //根据用户权限来确定是否显示该菜单
                if(\Yii::$app->user->can($child->url)){
                    $items[] =  ['label'=>$child->name,'url'=>[$child->url]];
                }
            }

            //$items[] =  ['label'=>'分类列表','url'=>['goods/ztree']];


            $menuItem = ['label'=>$menu->name,'items'=>$items];
            //将该组菜单放入菜单组里面
            //如果没有二级菜单,则不显示一级菜单
            if($items){
                $menuItems[] = $menuItem;

            }
        }

        return $menuItems;
    }



    /**
     * Finds an identity by the given ID.
     * @param string|int $id the ID to be looked for
     * @return IdentityInterface the identity object that matches the given ID.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentity($id)
    {
        return self::findOne(['id'=>$id]);
    }

    /**
     * Finds an identity by the given token.
     * @param mixed $token the token to be looked for
     * @param mixed $type the type of the token. The value of this parameter depends on the implementation.
     * For example, [[\yii\filters\auth\HttpBearerAuth]] will set this parameter to be `yii\filters\auth\HttpBearerAuth`.
     * @return IdentityInterface the identity object that matches the given token.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        // TODO: Implement findIdentityByAccessToken() method.
    }

    /**
     * Returns an ID that can uniquely identify a user identity.
     * @return string|int an ID that uniquely identifies a user identity.
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns a key that can be used to check the validity of a given identity ID.
     *
     * The key should be unique for each individual user, and should be persistent
     * so that it can be used to check the validity of the user identity.
     *
     * The space of such keys should be big enough to defeat potential identity attacks.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     * @return string a key that is used to check the validity of a given identity ID.
     * @see validateAuthKey()
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * Validates the given auth key.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     * @param string $authKey the given auth key
     * @return bool whether the given auth key is valid.
     * @see getAuthKey()
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }
}