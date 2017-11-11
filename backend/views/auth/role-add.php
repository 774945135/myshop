<?php

$form = \yii\bootstrap\ActiveForm::begin();

//角色名称
echo $form->field($model,'name')->textInput();
//角色描述
echo $form->field($model,'description')->textInput();
//限权
echo $form->field($model,'permissions',['inline'=>1])->checkboxList($permissions);

echo \yii\helpers\Html::submitButton('提交',['class'=>'btn btn-info']);

\yii\bootstrap\ActiveForm::end();
