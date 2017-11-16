<?php

$form = \yii\bootstrap\ActiveForm::begin();

//用户名
echo $form->field($model,'username')->textInput();

//密码
echo $form->field($model,'password')->passwordInput();

//验证码
echo $form->field($model,'code')->widget(\yii\captcha\Captcha::className(),[
    'template'=>'<div class="row"><div class="col-lg-1">{input}</div><div class="col-lg-1">{image}</div></div>'
]);

//自动登陆
echo $form->field($model,'cookie')->checkbox();

//提交
echo \yii\helpers\Html::submitButton('提交',['class'=>'btn btn-info']);

\yii\bootstrap\ActiveForm::end();

