<?php
/**
 * @var $this \yii\web\View
 */

$form = \yii\bootstrap\ActiveForm::begin();
//品牌名称
echo $form->field($model,'name')->textInput();

//简介
echo $form->field($model,'intro')->textarea();

//LOGO
//echo $form->field($model,'imgFile')->fileInput();
echo $form->field($model,'logo')->hiddenInput();

//=======================uploads==========================
//注册js和css
$this->registerCssFile('@web/webuploader/webuploader.css');
$this->registerJsFile('@web/webuploader/webuploader.js',[    'depends'=>\yii\web\JqueryAsset::className()
]);

$url = \yii\helpers\Url::to(['brand/uploads']);
$this->registerJs(
    <<<JS
    // 初始化Web Uploader
var uploader = WebUploader.create({

    // 选完文件后，是否自动上传。
    auto: true,

    // swf文件路径
    swf: '/js/Uploader.swf',

    // 文件接收服务端。
    server: '{$url}',

    // 选择文件的按钮。可选。
    // 内部根据当前运行是创建，可能是input元素，也可能是flash.
    pick: '#filePicker',

    // 只允许选择图片文件。
    accept: {
        title: 'Images',
        extensions: 'gif,jpg,jpeg,bmp,png',
        mimeTypes: 'image/jpg,image/png,image/gif'
    }
});
// 文件上传成功，给item添加成功class, 用样式标记上传成功。
    uploader.on( 'uploadSuccess', function( file ,response) {
    //$( '#'+file.id ).addClass('upload-state-done');
    $('#img').attr('src',response.url);
    //给logo赋值
    $('#brand-logo').val(response.url);
});
JS
);
?>
<!--dom结构部分-->
<div id="uploader-demo">
    <!--用来存放item-->
    <div id="fileList" class="uploader-list"></div>
    <div id="filePicker">选择图片</div>
</div>
    <div><img id="img" width="100"></div>
<?php

//=======================uploads==========================
//排序
echo $form->field($model,'sort')->textInput();

//状态
echo $form->field($model,'status',['inline'=>1])->radioList(['0'=>'隐藏','1'=>'正常']);

//提交按钮
echo \yii\bootstrap\Html::submitButton('提交',['class','btn btn-info']);

\yii\bootstrap\ActiveForm::end();

