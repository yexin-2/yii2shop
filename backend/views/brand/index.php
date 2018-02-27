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
    <tr>
        <td><?=$brand->id?></td>
        <td><?=$brand->name?></td>
        <td><?=$brand->intro?></td>
        <td><?=\yii\bootstrap\Html::img($brand->logo,['width'=>'50px'])?></td>
        <td><?=$brand->sort?></td>
        <td><?=$brand->is_deleted?"删除":"正常"?></td>
        <td><?php if($brand->is_deleted){
                echo \yii\bootstrap\Html::a('恢复',null,['class'=>'btn btn-warning']);
            }else{echo \yii\bootstrap\Html::a('修改',['brand/edit','id'=>$brand->id],['class'=>'btn btn-warning']);
        echo \yii\bootstrap\Html::a('删除',['brand/delete','id'=>$brand->id],['class'=>'btn btn-danger']);}?></td>
    </tr>
    <?php endforeach;?>
    <tr>
        <td colspan="7"><?=\yii\bootstrap\Html::a('添加',['brand/add'],['class'=>'btn btn-info'])?></td>
    </tr>
</table>
<?php
echo \yii\widgets\LinkPager::widget([
    'pagination'=>$pager
]);
