<table class="table table-bordered">
    <tr>
        <th>id</th>
        <th>用户名</th>
        <th>邮箱</th>
        <th>状态</th>
        <th>创建时间</th>
        <th>修改时间</th>
        <th>最后登录时间</th>
        <th>最后登陆ip字段</th>
        <th>操作</th>
    </tr>
    <?php foreach ($admins as $admin):?>
        <tr date-id="<?=$admin->id?>">
            <td><?=$admin->id?></td>
            <td><?=$admin->username?></td>
            <td><?=$admin->email?></td>
            <td><?=$admin->status?"启用":"禁用"?></td>
            <td><?=date('Y-m-d',$admin->created_at)?></td>
            <td><?=date('Y-m-d',$admin->updated_at)?></td>
            <td><?=date('Y-m-d',$admin->last_login_time)?></td>
            <td><?=$admin->last_login_ip?></td>
            <td><?php if (\Yii::$app->user->can('admin/edit')){echo \yii\bootstrap\Html::a('修改',['admin/edit','id'=>$admin->id],['class'=>'btn btn-warning']);}?>
                <?php if (\Yii::$app->user->can('admin/ajax-del')){echo \yii\bootstrap\Html::a('删除',null,['class'=>'btn btn-danger del']);}?>
                <?php if (\Yii::$app->user->can('admin/update-pwd')){echo \yii\bootstrap\Html::a('重置密码',['admin/update-pwd','id'=>$admin->id],['class'=>'btn btn-info']);}?></td>
        </tr>
    <?php endforeach;?>
    <?php if (\Yii::$app->user->can('admin/add')){
    echo '<tr>
        <td colspan="9">'.\yii\bootstrap\Html::a('添加',['admin/add'],['class'=>'btn btn-info']).'</td>
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
$url=\yii\helpers\Url::to(['admin/ajax-del']);
$this->registerJs(<<<JS
    $('.del').click(function() {
        if (confirm('你确定删除吗?')){
            var tr=$(this).closest('tr');
            $.get('{$url}',{'id':tr.attr('date-id')},function(v) {
              if (v=='yes'){
                  tr.fadeOut();
                  alert('删除成功');
              }
            },'json')
        }
    })
JS
);