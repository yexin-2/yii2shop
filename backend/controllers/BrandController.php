<?php
//品牌
namespace backend\controllers;

use backend\models\Brand;
use yii\data\Pagination;
use yii\web\UploadedFile;

class BrandController extends \yii\web\Controller
{
    public $enableCsrfValidation=false;//关闭跨域验证
    public function actionIndex()
    {
        $query=Brand::find();
        $pager=new Pagination();
        $pager->totalCount=$query->count();
        $pager->defaultPageSize=3;
        $brands=$query->offset($pager->offset)->limit($pager->limit)->all();
        return $this->render('index',['brands'=>$brands,'pager'=>$pager]);
    }
    //添加
    public function actionAdd(){
        $model=new Brand();
        $request=\Yii::$app->request;
        if ($request->isPost){
            $model->load($request->post());
//            $model->imgFile=UploadedFile::getInstance($model,'imgFile');
            if ($model->validate()){
//                if ($model->imgFile){
//                    $file="/upload/brand/".uniqid().".".$model->imgFile->extension;
//                    if ($model->imgFile->saveAs(\Yii::getAlias("@webroot").$file,0)){
//                        $model->logo=$file;
//                    }
//                }
                $model->is_deleted=0;
                $model->save();
                \Yii::$app->session->setFlash('success','添加成功');
                return $this->redirect(['brand/index']);
            }else{
                var_dump($model->getErrors());exit;
            }
        }
        return $this->render('add',['model'=>$model]);
    }
    //修改
    public function actionEdit($id){
        $model=Brand::findOne(['id'=>$id]);
        $request=\Yii::$app->request;
        if ($request->isPost){
            $model->load($request->post());
//            $model->imgFile=UploadedFile::getInstance($model,'imgFile');
            if ($model->validate()){
                /*if ($model->imgFile){
                    $file="/upload/brand/".uniqid().".".$model->imgFile->extension;
                    if ($model->imgFile->saveAs(\Yii::getAlias("@webroot").$file,0)){
                        $model->logo=$file;
                    }
                }*/
                $model->is_deleted=0;
                $model->save();
                \Yii::$app->session->setFlash('success','修改成功');
                return $this->redirect(['brand/index']);
            }else{
                var_dump($model->getErrors());exit;
            }
        }
        return $this->render('add',['model'=>$model]);
    }
    //删除
    public function actionDelete($id){
        $model=Brand::findOne(['id'=>$id]);
        if ($model->validate()){
            $model->is_deleted=1;
            $model->save();
            \Yii::$app->session->setFlash('success','删除成功');
            return $this->redirect(['brand/index']);
        }else{
            var_dump($model->getErrors());exit;
        }
    }
    //处理Web Uploader上传图片
    public function actionUpload(){
        //实例化上传文件类
        $imgFile=UploadedFile::getInstanceByName('file');
        $file='/upload/brand/'.uniqid().".".$imgFile->extension;
        if ($imgFile->saveAs(\Yii::getAlias('@webroot').$file,0)){
            //返回路径
            return  json_encode([
                'url'=>$file
            ]);
        };
    }
    //
    public function actionTest(){
    // 引入鉴权类
//    use Qiniu\Auth;
    // 引入上传类
//    use Qiniu\Storage\UploadManager;
    // 需要填写你的 Access Key 和 Secret Key
        $accessKey ="your accessKey";
        $secretKey = "your secretKey";
    $bucket = "your bucket name";
    // 构建鉴权对象
    $auth = new Auth($accessKey, $secretKey);
    // 生成上传 Token
    $token = $auth->uploadToken($bucket);
    // 要上传文件的本地路径
    $filePath = './php-logo.png';
    // 上传到七牛后保存的文件名
    $key = 'my-php-logo.png';
    // 初始化 UploadManager 对象并进行文件的上传。
    $uploadMgr = new UploadManager();
    // 调用 UploadManager 的 putFile 方法进行文件的上传。
    list($ret, $err) = $uploadMgr->putFile($token, $key, $filePath);
    echo "\n====> putFile result: \n";
    if ($err !== null) {
    var_dump($err);
    } else {
    var_dump($ret);
    }
    }
}
