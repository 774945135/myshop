<table class="table">
    <tr>
        <th>菜单名称</th>
        <th>路由</th>
        <th>操作</th>
    </tr>
    <?php foreach ($models as $model):?>
    <tr>
        <td><?=$model->name?></td>
        <td><?=$model->url?></td>
        <td><a href="<?=\yii\helpers\Url::to(['list/edit'])?>?id=<?=$model->id?>" class="btn btn-warning">修改</a>
            <a href="javascript:;" class="del btn btn-danger" id="<?=$model->id?>">删除</a></td>
    </tr>
    <?php endforeach;?>
</table>

<?php
/**
 * @var $this \yii\web\View
 */
    \yii\widgets\LinkPager::widget([
        'pagination'=>$pager
    ]);

    $url = \yii\helpers\Url::to(['list/del']);
    $this->registerJs(
            <<<JS
    $('.del').click(function() {
      if(confirm('您确定删除吗?')){
          var id = $(this).attr('id');
          var url = '{$url}';
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