<table class="table table-bordered">
    <tr>
        <th>id</th>
        <th>名称</th>
        <th>简介</th>
        <th>操作</th>
    </tr>
    <?php foreach ($goodsCategorys as $goodsCategory):?>
    <tr date-id="<?=$goodsCategory->id?>">
        <td><?=$goodsCategory->id?></td>
        <td><?php for($i=0;$i<=$goodsCategory->depth;$i++){echo "-";}echo $goodsCategory->name?></td>
        <td><?=$goodsCategory->intro?></td>
        <td><?php if (\Yii::$app->user->can('goods-category/edit')){echo \yii\bootstrap\Html::a('修改',['goods-category/edit','id'=>$goodsCategory->id],['class'=>'btn btn-warning']);}?>
            <?php if (\Yii::$app->user->can('goods-category/ajax-del')){echo \yii\bootstrap\Html::a('删除',null,['class'=>'btn btn-danger del']);}?></td>
    </tr>
    <?php endforeach;?>
<?php if (\Yii::$app->user->can('goods-category/add')){
    echo '<tr>
        <td colspan="9">'.\yii\bootstrap\Html::a('添加',['goods-category/add'],['class'=>'btn btn-info']).'</td>
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
$url=\yii\helpers\Url::to(['goods-category/ajax-del']);
$this->registerJs(<<<JS
    $('.del').click(function() {
        if (confirm('你确定删除吗?')){
            var tr=$(this).closest('tr');
            $.get('{$url}',{'id':tr.attr('date-id')},function(v) {
              if (v=='yes'){
                  tr.fadeOut();
              }else if (v=='no'){
                  alert('该分类为根分类,不能删除')
              }else {
                  alert('该分类下有子分类')
              }
            },'json')
        }
    });
JS
);