<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'language'=>'zh-CN',//显示中文
    'layout'=>false,
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-frontend',
        ],
        'user' => [
            'loginUrl'=>['member/login'],//登录地址
            'identityClass' => 'frontend\models\Member',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-frontend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the frontend
            'name' => 'advanced-frontend',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],

        'urlManager' => [
            'class'=>\yii\web\UrlManager::class,
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'suffix'=>'.html',//伪静态
            'rules' => [
            ],
        ],

        'sms' => [
            'class'=>\frontend\aliyun\SmsHandler::class,
            'ak'=>'LTAIENqt8xrTlIy8',
            'sk'=>'5q5RYaFX5PXPvNmEmvd8jKS2lkhZd8',
            'sign'=>'小叶茶坊',
            'template'=>'SMS_126915009'
        ]

    ],
    'params' => $params,
];
