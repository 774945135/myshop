<?php

$form = \yii\bootstrap\ActiveForm::begin();

//路由
echo $form->field($model,'name')->textInput();
//描述
echo $form->field($model,'description')->textInput();

echo \yii\helpers\Html::submitButton('提交',['class'=>'btn btn-info']);

\yii\bootstrap\ActiveForm::end();
