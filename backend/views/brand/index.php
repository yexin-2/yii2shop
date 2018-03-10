<table class="table table-bordered">
    <tr>
        <th>id</th>
        <th>名称</th>
        <th>简介</th>
        <th>LOGO图片</th>
        <th>排序</th>
        <th>状态</th>
        <th>操作</th>
    </tr>
    <?php foreach ($brands as $brand):?>
    <tr date-id="<?=$brand->id?>">
        <td><?=$brand->id?></td>
        <td><?=$brand->name?></td>
        <td><?=$brand->intro?></td>
        <td><?=\yii\bootstrap\Html::img($brand->logo,['width'=>'50px'])?></td>
        <td><?=$brand->sort?></td>
        <td><?=$brand->is_deleted?"删除":"正常"?></td>
        <td><?php
            if (\Yii::$app->user->can('brand/edit')){
                echo \yii\bootstrap\Html::a('修改',['brand/edit','id'=>$brand->id],['class'=>'btn btn-warning']);
            }
                if (\Yii::$app->user->can('brand/ajax-del')){
                    echo \yii\bootstrap\Html::a('删除',null,['class'=>'btn btn-danger del']);
                }?></td>
    </tr>
    <?php endforeach;?>
    <?php if (\Yii::$app->user->can('brand/edit')){
        echo '<tr>
        <td colspan="7">'.
            \yii\bootstrap\Html::a('添加',['brand/add'],['class'=>'btn btn-info']).'</td>
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
$url=\yii\helpers\Url::to(['brand/ajax-del']);
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