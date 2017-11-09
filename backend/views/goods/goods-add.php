<?php
/**
 * @var $this \yii\web\View
 */

$form = \yii\bootstrap\ActiveForm::begin();
//商品名称
echo $form->field($model,'name')->textInput();
//Logo
echo $form->field($model,'logo')->hiddenInput();
//============================Uploader======================================
//注册js和css
$this->registerCssFile('@web/webuploader/webuploader.css');
$this->registerJsFile('@web/webuploader/webuploader.js',[    'depends'=>\yii\web\JqueryAsset::className()
]);

$url = \yii\helpers\Url::to(['goods/uploads']);
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
        //console.debug(file);
        //console.debug(response);
    $('#img').attr('src',response.url);
    //给logo赋值
    $('#goods-logo').val(response.url);
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
    <div><img src="<?=$model->logo?>" id="img" width="100"></div>
<?php
//============================Uploader======================================
//商品分类id
echo $form->field($model,'goods_category_id')->hiddenInput();
//==============ZTREE=====================
//加载ztree静态资源 css js
$this->registerCssFile('@web/zTree/css/zTreeStyle/zTreeStyle.css');
$this->registerJsFile('@web/zTree/js/jquery.ztree.core.js',[
    'depends'=>\yii\web\JqueryAsset::className()
]);
$nodes = \yii\helpers\Json::encode(\yii\helpers\ArrayHelper::merge([['id'=>0,'parent_id'=>0,'name'=>'顶级分类']],\backend\models\GoodsCategory::getZtreeNodes()));
$this->registerJs(
    <<<JS
var zTreeObj;
        // zTree 的参数配置，深入使用请参考 API 文档（setting 配置详解）
        var setting = {
            callback:{
                onClick: function(event, treeId, treeNode){
                    //获取被点击节点的id
                    var id= treeNode.id;
                    //将id写入parent_id的值
                    $("#goods-goods_category_id").val(id);
                }
            }
            ,
            data: {
                simpleData: {
                    enable: true,
                    idKey: "id",
                    pIdKey: "parent_id",
                    rootPId: 0
                }
            }
        };
        // zTree 的数据属性，深入使用请参考 API 文档（zTreeNode 节点数据详解）
        var zNodes = {$nodes};
        
        zTreeObj = $.fn.zTree.init($("#treeDemo"), setting, zNodes);
        //展开所有节点
        zTreeObj.expandAll(true);
        //选中节点(回显)   
        //获取节点  ,根据节点的id搜索节点
        var node = zTreeObj.getNodeByParam("id", {$model->goods_category_id}, null);   
        zTreeObj.selectNode(node);
        
JS

);
echo '<div>
    <ul id="treeDemo" class="ztree"></ul>
</div>';
//=========================================
//品牌分类
echo $form->field($model,'brand_id')->dropDownList(\backend\models\Brand::getBrand_id());
//市场价格
echo $form->field($model,'market_price')->textInput();
//商品价格
echo $form->field($model,'shop_price')->textInput();
//库存
echo $form->field($model,'stock')->textInput();
//是否在售
echo $form->field($model,'is_on_sale')->dropDownList(['1'=>'在售','0'=>'下架']);
//状态
echo $form->field($model,'status',['inline'=>1])->radioList(['1'=>'上架','0'=>'下架']);
//排序
echo $form->field($model,'sort')->textInput();

//商品详细
//echo $form->field($intro,'content')->textarea();
echo $form->field($intro,'content')->widget('\kucha\ueditor\UEditor',[]);

//提交
echo \yii\helpers\Html::submitButton('提交',['class'=>'btn btn-info']);

\yii\bootstrap\ActiveForm::end();