
<!--dom结构部分-->
<div id="uploader-demo">
    <!--用来存放item-->
    <div id="fileList" class="uploader-list"></div>
    <div id="filePicker">选择图片</div>
</div>
<table class="table">
    <tr>
        <th>图片</th>
        <th>操作</th>
    </tr>
    <?php foreach ($models as $model):?>
    <tr>
        <td><?=\yii\helpers\Html::img($model->path,['width'=>100])?></td>
        <td><a href="javascript:;" class="del btn btn-danger" id="<?=$model->id?>">删除</a></td>
    </tr>
        <tbody id="myid"></tbody>
    <?php endforeach;?>
</table>
<?php
/**
 * @var $this \yii\web\View
 * @var $img \yii\helpers\Html
 */
$this->registerCssFile('@web/webuploader/webuploader.css');
$this->registerJsFile('@web/webuploader/webuploader.js',[
    'depends'=>\yii\web\JqueryAsset::className()
]);
$url2 = \yii\helpers\Url::to(['goods/photo-del']);
$url = \yii\helpers\Url::to(['goods/photo-add?id='.$id]);
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
        mimeTypes: 'image/jpg,image/jpeg,image/png'
    }
});
// 文件上传成功，给item添加成功class, 用样式标记上传成功。
uploader.on( 'uploadSuccess', function( file ,response ) {
   // $( '#'+file.id ).addClass('upload-state-done');
    //console.debug(file);
        alert('添加图片成功');
        //$('#tr').append('<td>'+img(response.url)+'</td><td><a href="javascript:;" class="del btn btn-danger" id="<?='+$model->id+'?>">'+删除+'</a></td>')
        $("#myid").html(file);//要刷新的div
  
   
});

    $('.del').click(function() {
      if(confirm('您确定要删除吗?删除后将无法恢复!')){
          var id = $(this).attr('id')
          var url = '{$url2}';
          var that = this;
          $.post(url,{id:id},function(data) {
                if(data == 'success'){
                    $(that).closest('tr').fadeOut();
                }else {
                    alert(data);
                }
          })
      }
    })

JS

);
?>