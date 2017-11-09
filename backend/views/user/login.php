<?php

$form = \yii\bootstrap\ActiveForm::begin();

//用户名
echo $form->field($model,'username')->textInput();

//旧密码
echo $form->field($model,'password')->passwordInput();

//自动登陆
echo $form->field($model,'cookie')->checkbox();

//提交
echo \yii\helpers\Html::submitButton('提交',['class'=>'btn btn-info']);

\yii\bootstrap\ActiveForm::end();

