<h1>文章列表</h1>
<a href="<?=\yii\helpers\Url::to(['article/article-add'])?>" class="btn btn-primary">添加</a>
<a href="<?=\yii\helpers\Url::to(['article/article-return'])?>" class="btn btn-primary">回收站</a>

<table class="table">
    <tr>
        <th>文章标题</th>
        <th>简介</th>
        <th>文章分类</th>
        <th>排序</th>
        <th>时间</th>
        <th>操作</th>

    </tr>
    <tbody id="mytb">
    <?php foreach ($models as $model):?>
        <tr>
            <td><?=$model->name?></td>
            <td><?=$model->intro?></td>
            <td><?=$model->category->name?></td>
            <td><?=$model->sort?></td>
            <td><?=date('Y:m:d H:i:s',$model->create_time)?></td>
            <td><a href="<?=\yii\helpers\Url::to(['article/article-edit'])?>?id=<?=$model->id?>" class="btn btn-success">修改</a>
                <a href="javascript:;" class="del btn btn-danger" id="<?=$model->id?>">删除</a></td>
        </tr>
    <?php endforeach;?>
    </tbody>
</table>
<?php

echo \yii\widgets\LinkPager::widget([
    'pagination'=>$pager
]);

$url = \yii\helpers\Url::to(['article/article-del']);
$this->registerJs(
    <<<JS
    $(".del").click(function(){
        if(confirm('是否删除该用户?删除后无法恢复!')){
    var url = "{$url}";
    var id = $(this).attr('id');
    var that = this;
    $.post(url,{id:id},function(data){
        if(data == 'yes'){
            //删除成功
            //alert('删除成功');
            $(that).closest('tr').fadeOut();
        }else{
            //删除失败
            alert(data);
        }
    });
        }
    });
JS

);