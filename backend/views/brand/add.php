<?php


$form = \yii\bootstrap\ActiveForm::begin();
//品牌名称
echo $form->field($model,'name')->textInput();

//简介
echo $form->field($model,'intro')->textarea();

//LOGO
echo $form->field($model,'imgFile')->fileInput();

//排序
echo $form->field($model,'sort')->textInput();

//状态
echo $form->field($model,'status',['inline'=>1])->radioList(['0'=>'隐藏','1'=>'正常']);

//提交按钮
echo \yii\bootstrap\Html::submitButton('提交',['class','btn btn-info']);

\yii\bootstrap\ActiveForm::end();
