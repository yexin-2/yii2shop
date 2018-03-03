<?php
$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'goods_id')->dropDownList(\backend\models\Goods::getGoods());
echo $form->field($model,'path')->hiddenInput();
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
    $("#goodsgallery-path").val(imgFile);
});
JS
);
if ($model->path==""){
    echo "<img id='photo' width='200px' >";
}else{
    echo "<img id='photo' width='200px' src='{$model->path}'>";
}
echo "<br>";
echo "<button type='submit' class='btn btn-primary'>".($model->getIsNewRecord()?'添加':'更新')."</button>";
\yii\bootstrap\ActiveForm::end();