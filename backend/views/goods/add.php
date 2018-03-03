<?php
$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name')->textInput();

echo $form->field($model,'logo')->hiddenInput();
//使用webupload插件
/**
 * @var $this \yii\web\View
 */
$this->registerCssFile("@web/webuploader/webuploader.css");
$this->registerJsFile("@web/webuploader/webuploader.js",[
    'depends'=>\yii\web\JqueryAsset::className()
]);
echo <<<HTML
<div id="uploader-demo">
    <!--用来存放item-->
    <div id="fileList" class="uploader-list"></div>
    <div id="filePicker">选择图片</div>
</div>
HTML;
$file=\yii\helpers\Url::to(['brand/upload']);
$this->registerJs(
    <<<JS
// 初始化Web Uploader
var uploader = WebUploader.create({

    // 选完文件后，是否自动上传。
    auto: true,

    // swf文件路径
    swf: '/js/Uploader.swf',

    // 文件接收服务端。
    server: '{$file}',

    // 选择文件的按钮。可选。
    // 内部根据当前运行是创建，可能是input元素，也可能是flash.
    pick: '#filePicker',

    // 只允许选择图片文件。
    accept: {
        title: 'Images',
        extensions: 'gif,jpg,jpeg,bmp,png',
        mimeTypes: 'image/gif,image/jpg,image/jpeg,image/bmp,image/png'
    }
});
// 文件上传成功，给item添加成功class, 用样式标记上传成功。
uploader.on( 'uploadSuccess', function( file,response ) {
    var imgFile=response.url;
    $("#photo").attr('src',imgFile);
    $("#goods-logo").val(imgFile);
});
JS
);
if ($model->logo==""){
    echo "<img id='photo' width='200px' >";
}else{
    echo "<img id='photo' width='200px' src='{$model->logo}'>";
}


echo $form->field($model,'goods_category_id')->hiddenInput();
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
		    $('#goods-goods_category_id').val(treeNode.id);
		    }
	    }
   };
   // zTree 的数据属性，深入使用请参考 API 文档（zTreeNode 节点数据详解）
   var zNodes = {$nodes};
   zTreeObj = $.fn.zTree.init($("#treeDemo"), setting, zNodes);
   zTreeObj.expandAll(true);//默认展开
   var node = zTreeObj.getNodeByParam("id", "{$model3->id}", null);//找到节点id
   zTreeObj.selectNode(node);//选中
JS
);
echo '<div>
   <ul id="treeDemo" class="ztree"></ul>
</div>';

echo $form->field($model,'brand_id')->dropDownList(\backend\models\Brand::getBrand());
echo $form->field($model,'market_price')->textInput();
echo $form->field($model,'shop_price')->textInput();
echo $form->field($model,'stock')->textInput();
echo $form->field($model,'is_on_sale',['inline'=>1])->radioList([
    '下架','在售'
]);
echo $form->field($model,'sort')->textInput();
echo $form->field($model2,'content')->widget('kucha\ueditor\UEditor',[]);
echo "<button type='submit' class='btn btn-primary'>".($model->getIsNewRecord()?'添加':'更新')."</button>";
\yii\bootstrap\ActiveForm::end();