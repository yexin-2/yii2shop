<table class="table table-bordered">
    <tr>
        <th>id</th>
        <th>名称</th>
        <th>简介</th>
        <th>排序</th>
        <th>状态</th>
        <th>操作</th>
    </tr>
    <?php foreach ($articleCategorys as $articleCategory):?>
    <tr date-id="<?=$articleCategory->id?>">
        <td><?=$articleCategory->id?></td>
        <td><?=$articleCategory->name?></td>
        <td><?=$articleCategory->intro?></td>
        <td><?=$articleCategory->sort?></td>
        <td><?=$articleCategory->is_deleted?"删除":"正常"?></td>
        <td><?php if (\Yii::$app->user->can('article-category/edit')){echo  \yii\bootstrap\Html::a('修改',['article-category/edit','id'=>$articleCategory->id],['class'=>'btn btn-info']);}
            if (\Yii::$app->user->can('article-category/ajax-del')){
        echo \yii\bootstrap\Html::a('删除',null,['class'=>'btn btn-info del']);}?></td>
    </tr>
    <?php endforeach;?>
<?php if (\Yii::$app->user->can('article-category/add')){
    echo '<tr>
        <td colspan="7">'.\yii\bootstrap\Html::a('添加',['article-category/add'],['class'=>'btn btn-info']).'</td>
    </tr>';
}
?>
</table>
<?php
echo \yii\widgets\LinkPager::widget([
    'pagination'=>$pager
]);
/**
 * @var $this \yii\web\View
 */
$url=\yii\helpers\Url::to(['article-category/ajax-del']);
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