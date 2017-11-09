<?php $form = \yii\bootstrap\ActiveForm::begin(['action'=>\yii\helpers\Url::to(['goods/goods-index']),'method'=>'get']);?>

    <div class="row">
        <div class="col-md-2">
            <?=$form->field($model,'title')->textInput();?>
        </div>
    </div>
<?=\yii\helpers\Html::submitButton('搜索',['class'=>'btn btn-info']);?>
<?php \yii\bootstrap\ActiveForm::end(); ?>
<a href="<?=\yii\helpers\Url::to(['goods/goods-add'])?>">添加</a>
<table class="table">
    <tr>
        <th>ID</th>
        <th>货号</th>
        <th>名称</th>
        <th>价格</th>
        <th>库存</th>
        <th>LOGO</th>
        <th>操作</th>
    </tr>
    <?php foreach ($models as $model):?>
    <tr>
        <td><?=$model->id?></td>
        <td><?=$model->sn?></td>
        <td><?=$model->name?></td>
        <td><?=$model->shop_price?></td>
        <td><?=$model->stock?></td>
        <td><?=\yii\helpers\Html::img($model->logo,['width'=>50])?></td>
        <td><a class="btn btn-default" href="<?=\yii\helpers\Url::to(['goods/photos-index'])?>?id=<?=$model->id?>">相册</a>
            <a class="btn btn-warning" href="<?=\yii\helpers\Url::to(['goods/goods-edit'])?>?id=<?=$model->id?>">修改</a>
            <a href="javascript:;" class="del btn btn-danger" id="<?=$model->id?>">删除</a>
            <a class="btn btn-success" href="<?=\yii\helpers\Url::to(['goods/goods-view'])?>?id=<?=$model->id?>">预览</a></td>
    </tr>
    <?php endforeach;?>
</table>
<?php

echo \yii\widgets\LinkPager::widget([
        'pagination'=>$pager
    ]);


    /**
     * @var $this \yii\web\View
     */
$url = \yii\helpers\Url::to(['goods/goods-del']);
$this->registerJs(
        <<<JS
    $('.del').click(function() {
      if(confirm('您确定要删除吗?')){
          var id = $(this).attr('id');
          var url ='{$url}';
          var that = this;
          $.post(url,{id:id},function(data) {
            if(data == 'yes'){
                 $(that).closest('tr').fadeOut();
            }else {
                alert(data);
            }
          })
      }
    })



JS

    );