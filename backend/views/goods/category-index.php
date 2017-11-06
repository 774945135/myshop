<a href="<?=\yii\helpers\Url::to(['goods/category-add'])?>" class="btn btn-primary">添加</a>
<table class="table">
    <tr>
        <th>id</th>
        <th>分类名称</th>
        <th>操作</th>
    </tr>
    <?php foreach ($models as $model):?>
    <tr>
        <td><?=$model->id?></td>
        <td><?=str_repeat('--',$model->depth*3).$model->name?></td>
        <td><a href="<?=\yii\helpers\Url::to(['goods/category-edit'])?>?id=<?=$model->id?>" class="btn btn-primary">修改</a>
            <a href="javascript:;" class="del btn btn-danger" id="<?=$model->id?>">删除</a></td>
    </tr>
    <?php endforeach;?>
</table>

<?php
/**
 * @var $this \yii\web\View
 */
$url = \yii\helpers\Url::to(['goods/category-del']);
$this->registerJs(
    <<<js
    $('.del').click(function() {
      if(confirm('你确定要删除吗?删除后将无法恢复!')){
          var url = "{$url}";
          var id = $(this).attr('id');
          var that = this;
          $.post(url,{id:id},function(data) {
                if(data == 'success'){
                    //alert('删除成功');
                    $(that).closest('tr').fadeOut();
                }else {
                    alert(data);
                }
          })
      }
    })


js

);