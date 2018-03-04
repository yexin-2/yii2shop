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
        <tr>
            <td><?=$admin->id?></td>
            <td><?=$admin->username?></td>
            <td><?=$admin->email?></td>
            <td><?=$admin->status?"启用":"禁用"?></td>
            <td><?=date('Y-m-d',$admin->created_at)?></td>
            <td><?=date('Y-m-d',$admin->updated_at)?></td>
            <td><?=date('Y-m-d',$admin->last_login_time)?></td>
            <td><?=$admin->last_login_ip?></td>
            <td><?=\yii\bootstrap\Html::a('修改',['admin/edit','id'=>$admin->id],['class'=>'btn btn-warning'])?><?=\yii\bootstrap\Html::a('删除',['admin/delete','id'=>$admin->id],['class'=>'btn btn-danger'])?><?=\yii\bootstrap\Html::a('重置密码',['admin/update-pwd','id'=>$admin->id],['class'=>'btn btn-info'])?></td>
        </tr>
    <?php endforeach;?>
    <tr>
        <td colspan="9"><?=\yii\bootstrap\Html::a('添加',['admin/add'],['class'=>'btn btn-info'])?></td>
    </tr>
</table>
<?php
echo \yii\widgets\LinkPager::widget([
    'pagination'=>$pager
]);