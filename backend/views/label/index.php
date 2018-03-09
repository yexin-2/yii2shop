<table id="table_id_example" class="display">
    <thead>
    <tr>
        <th>名称</th>
        <th>路由</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($labels as $label):?>
        <tr date-id="<?=$label->id?>">
            <td><?=$label->name?></td>
            <td><?=$label->route?></td>
            <td><?php if (\Yii::$app->user->can('label/edit')){echo \yii\bootstrap\Html::a('修改',['label/edit','id'=>$label->id],['class'=>'btn btn-warning']);}?>
                <?php if (\Yii::$app->user->can('label/delete')){echo \yii\bootstrap\Html::a('删除',null,['class'=>'btn btn-danger del']);}?></td>
        </tr>
    <?php endforeach;?>
    </tbody>
<?php if (\Yii::$app->user->can('label/add')){
    echo '<tr>
        <td colspan="3">'.\yii\bootstrap\Html::a('添加',['label/add'],['class'=>'btn btn-info']).'</td>
    </tr>';
}
?>
</table>
<?php
/**
 * @var $this \yii\web\View
 */
$this->registerCssFile('@web/DataTables-1.10.15/media/css/jquery.dataTables.css');
$this->registerJsFile('@web/DataTables-1.10.15/media/js/jquery.dataTables.js',[
    'depends'=>\yii\web\JqueryAsset::class,
]);
$this->registerJs(<<<JS
    $(document).ready( function () {
        $('#table_id_example').DataTable({
            language: {
                "sProcessing": "处理中...",
            "sLengthMenu": "显示 _MENU_ 项结果",
            "sZeroRecords": "没有匹配结果",
            "sInfo": "显示第 _START_ 至 _END_ 项结果，共 _TOTAL_ 项",
            "sInfoEmpty": "显示第 0 至 0 项结果，共 0 项",
            "sInfoFiltered": "(由 _MAX_ 项结果过滤)",
            "sInfoPostFix": "",
            "sSearch": "搜索:",
            "sUrl": "",
            "sEmptyTable": "表中数据为空",
            "sLoadingRecords": "载入中...",
            "sInfoThousands": ",",
            "oPaginate": {
                "sFirst": "首页",
                "sPrevious": "上页",
                "sNext": "下页",
                "sLast": "末页"
            },
            "oAria": {
                "sSortAscending": ": 以升序排列此列",
                "sSortDescending": ": 以降序排列此列"
            }
        }
    });
 } );
JS
);
$url=\yii\helpers\Url::to(['label/delete']);
$this->registerJs(<<<JS
    $('.del').click(function() {
        if (confirm('你确定删除吗?')){
            var tr=$(this).closest('tr');
            $.get('{$url}',{'id':tr.attr('date-id')},function(v) {
              if (v=='yes'){
                  tr.fadeOut();
              }
            },'json')
        }
    })
JS
);
