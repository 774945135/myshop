<?php

$form = \yii\bootstrap\ActiveForm::begin();
//用户名
echo $form->field($model,'username')->textInput();
//密码
echo $form->field($model,'password_hash')->passwordInput();
//邮箱
echo $form->field($model,'email')->textInput();
//状态
echo $form->field($model,'status',['inline'=>1])->radioList([0=>'禁用',1=>'启用']);
//分配角色
echo $form->field($model,'roles',['inline'=>1])->checkboxList($roles);
//提交
echo \yii\helpers\Html::submitButton('提交',['class'=>'btn btn-info']);

\yii\bootstrap\ActiveForm::end();

