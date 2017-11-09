

<table class="table">
    <tr>
        <th>ID</th>
        <th>用户名</th>
        <th>邮箱</th>
        <th>状态</th>
        <th>操作</th>
    </tr>
    <?php foreach ($models as $model):?>
    <tr>
        <td><?=$model->id?></td>
        <td><?=$model->username?></td>
        <td><?=$model->email?></td>
        <td><?=$model->status==1?'启用':'禁止'?></td>
        <td><a class="btn btn-warning" href="<?=\yii\helpers\Url::to(['user/edit'])?>?id=<?=$model->id?>">修改</a>
            <a class="del btn btn-danger" id="<?=$model->id?>" href="javascript:;">删除</a></td>
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
$url = \yii\helpers\Url::to(['user/del']);
    $this->registerJs(
        <<<JS
    $('.del').click(function() {
      if(confirm('您确定要删除吗?')){
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
    });
JS

    );