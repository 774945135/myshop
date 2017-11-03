<?php


$form = \yii\bootstrap\ActiveForm::begin();
//名称
echo $form->field($model,'name')->textInput();

//分类
echo $form->field($model,'article_category_id')->dropDownList(\backend\models\ArticleCategory::getArticleCategorys());

//简介
echo $form->field($model,'intro')->textarea();

//排序
echo $form->field($model,'sort')->textInput();

//状态
echo $form->field($model,'status',['inline'=>1])->radioList(['0'=>'隐藏','1'=>'正常']);

//内容
echo $form->field($detail,'content')->textarea();


//提交按钮
echo \yii\bootstrap\Html::submitButton('提交',['class','btn btn-info']);

\yii\bootstrap\ActiveForm::end();
