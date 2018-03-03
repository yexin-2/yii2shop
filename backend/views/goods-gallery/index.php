<table class="table table-bordered">
    <tr>
        <th>id</th>
        <th>商品id</th>
        <th>图片地址</th>
        <th>操作</th>
    </tr>
    <?php foreach ($goodsGallerys as $goodsGallery):?>
    <tr>
        <td><?=$goodsGallery->id?></td>
        <td><?=\backend\models\Goods::getGoods()[$goodsGallery->goods_id]?></td>
        <td><?=\yii\bootstrap\Html::img($goodsGallery->path,['width'=>'50px'])?></td>
        <td><?=\yii\bootstrap\Html::a('删除',['goods-gallery/delete','id'=>$goodsGallery->id],['class'=>'btn btn-danger'])?></td>
    </tr>
    <?php endforeach;?>
    <tr>
        <td colspan="9"><?=\yii\bootstrap\Html::a('添加',['goods-gallery/add'],['class'=>'btn btn-info'])?></td>
    </tr>
</table>
<?php
echo \yii\widgets\LinkPager::widget([
    'pagination'=>$pager
]);
