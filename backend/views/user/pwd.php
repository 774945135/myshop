<?php

$form = \yii\bootstrap\ActiveForm::begin();

//旧密码
echo $form->field($model,'oldpassword')->passwordInput();
//新密码
echo $form->field($model,'newpassword')->passwordInput();
//确认密码
echo $form->field($model,'repassword')->passwordInput();

//提交
echo \yii\helpers\Html::submitButton('提交',['class'=>'btn btn-info']);

\yii\bootstrap\ActiveForm::end();

