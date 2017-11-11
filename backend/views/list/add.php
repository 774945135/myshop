<?php
$form = \yii\bootstrap\ActiveForm::begin();

echo $form->field($model,'name')->textInput();

echo $form->field($model,'parent_id')->dropDownList($models);

echo $form->field($model,'url')->dropDownList($url);

echo $form->field($model,'sort')->textInput();

echo \yii\helpers\Html::submitButton('提交',['class'=>'btn btn-info']);

\yii\bootstrap\ActiveForm::end();