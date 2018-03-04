<table class="table table-bordered">
    <tr>
        <th>id</th>
        <th>树id</th>
        <th>左值</th>
        <th>右值</th>
        <th>名称</th>
        <th>上级分类id</th>
        <th>简介</th>
        <th>操作</th>
    </tr>
    <?php foreach ($goodsCategorys as $goodsCategory):?>
    <tr>
        <td><?=$goodsCategory->id?></td>
        <td><?=$goodsCategory->tree?></td>
        <td><?=$goodsCategory->lft?></td>
        <td><?=$goodsCategory->rgt?></td>
        <td><?php for($i=0;$i<=$goodsCategory->depth;$i++){echo "-";}echo $goodsCategory->name?></td>
        <td><?=$goodsCategory->parent_id?></td>
        <td><?=$goodsCategory->intro?></td>
        <td><?=\yii\bootstrap\Html::a('修改',['goods-category/edit','id'=>$goodsCategory->id],['class'=>'btn btn-warning'])?><?=\yii\bootstrap\Html::a('删除',['goods-category/delete','id'=>$goodsCategory->id],['class'=>'btn btn-danger'])?></td>
    </tr>
    <?php endforeach;?>
    <tr>
        <td colspan="9"><?=\yii\bootstrap\Html::a('添加',['goods-category/add'],['class'=>'btn btn-info'])?></td>
    </tr>
</table>
<?php
echo \yii\widgets\LinkPager::widget([
    'pagination'=>$pager
]);
