<table id="table_id_example" class="display">
<thead>
    <tr>
        <th>名称(路由)</th>
        <th>描述</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($models as $model):  ?>
    <tr>
        <td><?=$model->name?></td>
        <td><?=$model->description?></td>
        <td><a class="btn btn-warning" href="<?=\yii\helpers\Url::to(['auth/perm-edit'])?>?name=<?=$model->name?>">修改</a>
            <a href="javascript:;" class="del btn btn-danger" id="<?=$model->name?>">删除</a></td>
    </tr>
    <?php endforeach;?>
    </tbody>
</table>

<?php
    /**
     * @var $this \yii\web\View
     */
$this->registerCssFile('@web/DataTables/DataTables-1.10.16/css/jquery.dataTables.css');
$this->registerJsFile('@web/DataTables/DataTables-1.10.16/js/jquery.dataTables.js',[
    'depends'=>\yii\web\JqueryAsset::className()
]);
    $url = \yii\helpers\Url::to(['auth/perm-del']);
    $this->registerJs(
        <<<JS
    $('.del').click(function() {
        if(confirm('您确定要删除吗?')){
            var name = $(this).attr('id');
            var url = '{$url}';
            var that = this;
            $.post(url,{name:name},function(data) {
                if(data == 'success'){
                    $(that).closest('tr').fadeOut();
                }else {
                    alert(data);
                }
            })
        }
    })
     $(document).ready( function () {
    $('#table_id_example').DataTable();
} );

JS

    );