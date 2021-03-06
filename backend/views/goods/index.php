<?php
$form=\yii\bootstrap\ActiveForm::begin(['method'=>'get','layout'=>'inline']);
echo $form->field($model,'g_name')->textInput(['placeholder'=>'商品名称']);
echo $form->field($model,'g_sn')->textInput(['placeholder'=>'货号']);
echo $form->field($model,'min_price')->textInput(['placeholder'=>'$']);
echo $form->field($model,'max_price')->textInput(['placeholder'=>'$']);
echo "<button type='submit' class='btn btn-primary'>搜索</button>";
\yii\bootstrap\ActiveForm::end();?>
<table class="table table-bordered">
    <tr>
        <th>id</th>
        <th>货号</th>
        <th>商品名称</th>
        <th>商品价格</th>
        <th>库存</th>
        <th>LOGO图片</th>
        <th>操作</th>
    </tr>
    <?php foreach ($goods as $good):?>
    <tr date-id="<?=$good->id?>">
        <td><?=$good->id?></td>
        <td><?=$good->sn?></td>
        <td><?=$good->name?></td>
        <td><?=$good->shop_price?></td>
        <td><?=$good->stock?></td>
        <td><?=\yii\bootstrap\Html::img($good->logo,['width'=>'50px'])?></td>
        <td><?php if (\Yii::$app->user->can('goods/pic')){echo \yii\bootstrap\Html::a('相册',['goods/pic','id'=>$good->id],['class'=>'btn btn-info']);}?>
            <?php if (\Yii::$app->user->can('goods/edit')){echo \yii\bootstrap\Html::a('修改',['goods/edit','id'=>$good->id],['class'=>'btn btn-warning']);}?>
            <?php if (\Yii::$app->user->can('goods/ajax-del')){echo \yii\bootstrap\Html::a('删除',null,['class'=>'btn btn-danger del']);}?></td>
    </tr>
    <?php endforeach;?>
<?php if (\Yii::$app->user->can('goods/add')){
    echo '<tr>
        <td colspan="9">'.\yii\bootstrap\Html::a('添加',['goods/add'],['class'=>'btn btn-info']).'</td>
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
$url=\yii\helpers\Url::to(['goods/ajax-del']);
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