<h1>回收站</h1><td><a href="<?=\yii\helpers\Url::to(['brand/index'])?>" class="btn btn-primary">返回品牌列表</a>
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

            <td><a href="<?=\yii\helpers\Url::to(['brand/ret'])?>?id=<?=$model->id?>" class="btn btn-success">还原</a>
                <a href="<?=\yii\helpers\Url::to(['brand/delete'])?>?id=<?=$model->id?>" class="btn btn-danger">彻底删除</a></td>
        </tr>
    <?php endforeach;?>
    </tbody>
</table>
