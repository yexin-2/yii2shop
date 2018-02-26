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
    <tr>
        <td><?=$articleCategory->id?></td>
        <td><?=$articleCategory->name?></td>
        <td><?=$articleCategory->intro?></td>
        <td><?=$articleCategory->sort?></td>
        <td><?=$articleCategory->is_deleted?"删除":"正常"?></td>
        <td><?php if($articleCategory->is_deleted){

            }else{echo \yii\bootstrap\Html::a('修改',['article-category/edit','id'=>$articleCategory->id],['class'=>'btn btn-info']);
        echo \yii\bootstrap\Html::a('删除',['article-category/delete','id'=>$articleCategory->id],['class'=>'btn btn-info']);}?></td>
    </tr>
    <?php endforeach;?>
    <tr>
        <td colspan="7"><?=\yii\bootstrap\Html::a('添加',['article-category/add'],['class'=>'btn btn-info'])?></td>
    </tr>
</table>
<?php
echo \yii\widgets\LinkPager::widget([
    'pagination'=>$pager
]);
