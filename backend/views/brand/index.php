<a href="<?=\yii\helpers\Url::to(['brand/add'])?>" class="btn btn-primary">添加</a>
<a href="<?=\yii\helpers\Url::to(['brand/return'])?>" class="btn btn-primary">回收站</a>

<table class="table">
    <tr>
        <th>品牌名称</th>
        <th>简介</th>
        <th>LOGO</th>
        <th>排序</th>
        <th>操作</th>

    </tr>
    <tbody id="mytb">
    <?php foreach ($models as $model):?>
        <tr>
            <td><?=$model->name?></td>
            <td><?=$model->intro?></td>
            <td><?=\yii\helpers\Html::img($model->logo,['width'=>50,'height'=>50])?></td>
            <td><?=$model->sort?></td>

            <td><a href="<?=\yii\helpers\Url::to(['brand/edit'])?>?id=<?=$model->id?>" class="btn btn-success">修改</a>
                <a href="javascript:;" class="del btn btn-danger" id="<?=$model->id?>">删除</a></td>
        </tr>
    <?php endforeach;?>
    </tbody>
</table>
<?php

echo \yii\widgets\LinkPager::widget([
     'pagination'=>$pager
]);

$url = \yii\helpers\Url::to(['brand/del']);
$this->registerJs(
    <<<JS
    $(".del").click(function(){
        if(confirm('是否删除该用户?删除后无法恢复!')){
    var url = "{$url}";
    var id = $(this).attr('id');
    var that = this;
    console.debug(url);
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