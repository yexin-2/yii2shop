<?php

/* @var $this \yii\web\View */
/* @var $content string */

use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use common\widgets\Alert;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => Yii::$app->name,
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    $menuItems = [
        ['label' => 'Home', 'url' => ['/site/index']],
//        ['label' => '文章分类', 'url' => ['/article-category/index'],
//            'items' => [
//                ['label' => '文章添加', 'url' => ['/article-category/add']],
//                ['label' => '文章列表', 'url' => ['/article-category/index']],
//            ]
//        ],
//        ['label' => '文章', 'url' => ['/article/index']],
//        ['label' => '品牌', 'url' => ['/brand/index']],
//        ['label' => '商品分类', 'url' => ['/goods-category/index']],
//        ['label' => '商品', 'url' => ['/goods/index']],
//        ['label' => '管理员', 'url' => ['/admin/index'],
//            'items' => [
//                ['label' => '用户添加', 'url' => ['/admin/add']],
//                ['label' => '用户列表', 'url' => ['/admin/index']],
//            ]
//        ],
//        ['label' => 'RBAC', 'url' => ['/rbac/index'],
//            'items' => [
//                ['label' => '权限添加', 'url' => ['/rbac/add-permission']],
//                ['label' => '权限列表', 'url' => ['/rbac/index-permission']],
//                ['label' => '角色添加', 'url' => ['/rbac/add-role']],
//                ['label' => '角色列表', 'url' => ['/rbac/index-role']],
//            ]
//        ],
//        ['label' => '菜单列表', 'url' => ['/label/index'],
//            'items' => [
//                ['label' => '菜单添加', 'url' => ['/label/add']],
//                ['label' => '菜单列表', 'url' => ['/label/index']],
//            ]
//        ]
    ];
    if (Yii::$app->user->isGuest) {
        $menuItems[] = ['label' => 'Login', 'url' => ['/admin/login']];
    } else {

        $menuItems=\backend\models\Label::getLabel($menuItems);

        $menuItems[] = '<li>'
            . Html::beginForm(['/admin/logout'], 'post')
            . Html::submitButton(
                'Logout (' . Yii::$app->user->identity->username . ')',
                ['class' => 'btn btn-link logout']
            )
            . Html::endForm()
            . '</li>';
        $menuItems[] = ['label' => '修改密码', 'url' => ['/admin/edit-pwd']];
    }
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => $menuItems,
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; <?= Html::encode(Yii::$app->name) ?> <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
