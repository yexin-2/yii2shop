<table class="table table-bordered">
    <tr>
        <th>id</th>
        <th>名称</th>
        <th>简介</th>
        <th>文章分类id</th>
        <th>排序</th>
        <th>状态</th>
        <th>创建时间</th>
        <th>操作</th>
    </tr>
    <?php foreach ($articles as $article):?>
        <tr>
            <td><?=$article->id?></td>
            <td><?=$article->name?></td>
            <td><?=$article->intro?></td>
            <td><?=\backend\models\ArticleCategory::getArticleCategory()[$article->article_category_id]?></td>
            <td><?=$article->sort?></td>
            <td><?=$article->is_deleted?"删除":"正常"?></td>
            <td><?=date('Y-m-d',$article->create_time)?></td>
            <td><?php if($article->is_deleted){

                }else{echo \yii\bootstrap\Html::a('修改',['article/edit','id'=>$article->id],['class'=>'btn btn-info']);
                    echo \yii\bootstrap\Html::a('删除',['article/delete','id'=>$article->id],['class'=>'btn btn-info']);
                    echo \yii\bootstrap\Html::a('查看',['article/look','id'=>$article->id],['class'=>'btn btn-info']);}?></td>
        </tr>
    <?php endforeach;?>
    <tr>
        <td colspan="8"><?=\yii\bootstrap\Html::a('添加',['article/add'],['class'=>'btn btn-info'])?></td>
    </tr>
</table>
<?php
echo \yii\widgets\LinkPager::widget([
    'pagination'=>$pager
]);
