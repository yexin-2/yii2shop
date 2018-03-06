<tbody id="g_tbody">
<?php foreach ($goodsGallerys as $goodsGallery):?>
    <tr>
        <td><?=\yii\bootstrap\Html::img($goodsGallery->path,['width'=>'100px'])?>
            <?=\yii\bootstrap\Html::a('删除',['goods/pic-del','id'=>$goodsGallery->id,'ord_id'=>$ord_id],['class'=>'btn btn-danger'])?></td><!--传一个删除用的id和回显用的id-->
    </tr>
<?php endforeach;?>
</tbody>
<?php
$form=\yii\bootstrap\ActiveForm::begin();
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
$ajaxUrl=\yii\helpers\Url::to(['goods/ajax']);
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
// $("#photo").attr('src',imgFile);
// $("#goodsgallery-path").val(imgFile);
$.post("{$ajaxUrl}", { path: imgFile, goods_id: $ord_id },function(v) {
 //    var img="";
 //  $("#g_tbody").each(function(){
 //      img.="<img id='photo' width='200px' >";
 //      var tr="<img id='photo' width='200px' >";
 // });
}, "json");//ajax提交能实现多文件上传
location.reload();//页面刷新
});
JS
);
//if ($model->path==""){
//echo "<img id='photo' width='200px' >";
//}else{
//echo "<img id='photo' width='200px' src='{$model->path}'>";
//}
echo "<br>";
echo "<button type='submit' class='btn btn-primary'>".($model->getIsNewRecord()?'添加':'更新')."</button>";
\yii\bootstrap\ActiveForm::end();