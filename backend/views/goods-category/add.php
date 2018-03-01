<?php
$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name')->textInput();
echo $form->field($model,'parent_id')->hiddenInput();
//ztree插件
/**
 * @var $this \yii\web\View
 */
$this->registerCssFile('@web/ztree/css/zTreeStyle/zTreeStyle.css');
$this->registerJsFile('@web/ztree/js/jquery.ztree.core.js',[
    'depends'=>\yii\web\JqueryAsset::class
]);
$this->registerJs(
    <<<JS
    var zTreeObj;
   // zTree 的参数配置，深入使用请参考 API 文档（setting 配置详解）
   var setting = {
       data: {
		simpleData: {
			enable: true,
			idKey: "id",
			pIdKey: "parent_id",
			rootPId: 0
		    }
		},
		callback: {
		onClick: function(event, treeId, treeNode) {
		    $('#goodscategory-parent_id').val(treeNode.id);
		    }
	    }
   };
   // zTree 的数据属性，深入使用请参考 API 文档（zTreeNode 节点数据详解）
   var zNodes = {$nodes};
   zTreeObj = $.fn.zTree.init($("#treeDemo"), setting, zNodes);
   zTreeObj.expandAll(true);//默认展开
   var node = zTreeObj.getNodeByParam("id", "{$model->parent_id}", null);//找到父节点id
   zTreeObj.selectNode(node);//选中
JS
);
echo '<div>
   <ul id="treeDemo" class="ztree"></ul>
</div>';
echo $form->field($model,'intro')->textarea();
echo "<button type='submit' class='btn btn-primary'>".($model->getIsNewRecord()?'添加':'更新')."</button>";
\yii\bootstrap\ActiveForm::end();