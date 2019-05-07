<?php
$params = require(__DIR__ . '/params.php');

$config = [
    'id' => 'Manage',
    'name' => '赛事管理系统',    
    'basePath' => dirname(__DIR__),
    'charset' => 'utf-8',
    'language' => 'zh-CN',
    'timeZone' => 'Asia/Shanghai',
    'bootstrap' => ['log'],
    'defaultRoute' => 'default/index',
    'modules' => [
        'user' => ['class' => 'app\modules\user\Module'],
        'league' => ['class' => 'app\modules\league\Module'],
        'statistics' => ['class' => 'app\modules\statistics\Module'],
        'admin' => ['class' =>'app\modules\admin\Module'],
        'news' => ['class' =>'app\modules\news\Module'],
        'notice' => ['class' =>'app\modules\notice\Module'],
        'rating' => ['class' => 'app\modules\rating\Module'],
        'recruit' => ['class' => 'app\modules\recruit\Module'],
        'club' => ['class' => 'app\modules\club\Module'],
        'message' => ['class' => 'app\modules\message\Module'],
        'mall' => ['class' => 'app\modules\mall\Module'],
        'pubg' => ['class' => 'app\modules\pubg\Module'],
        'finance' => ['class' => 'app\modules\finance\Module'],
    ],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'NTuXny7IHsfyoDsT2yGBwPI9xg6kowr3',
        ],
         'cache' => [
             'class' => 'yii\caching\MemCache',
             'servers' => [
                 [
                     'host' => '127.0.0.1',
                     'port' => 11211,
                     'weight' => 60,
                 ]
             ],
         ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
            'loginUrl' => '/login',
        ],
        'errorHandler' => [
            'errorAction' => 'error/index',
        ],
        'remoteRequest' => [
            'class' => 'app\components\Curl',
        ],
        'formatter' => [
            'dateFormat' => 'php:Y-m-d H:i:s',
            'decimalSeparator' => '.',
            'thousandSeparator' => ',',
            'nullDisplay' => '(无)',
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
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
//             'suffix' => '.html',
            'rules' => [
                    'user/<action:(update|lock|unlock|authorize|unbind|chat|detail|role|bindrole|bag|currency|currencylist)>' => 'user/default/<action>',
                    'league/<action:(create|update)>' => 'league/default/<action>',
                    'admin/<action:(create)>' => 'admin/default/<action>',
                    'news/<action:(create|update|upload|close|release)>' => 'news/default/<action>',
                    'notice/<action:(create|update|delete)>' => 'notice/default/<action>',
                    'rating/<action:(create|update|delete)>' => 'rating/default/<action>',
                    'recruit/<action:(create|update|delete|apply|medal)>' => 'recruit/default/<action>',
                    'club/<action:(create|update)>' => 'club/default/<action>',
                    'message/<action:(create)>' => 'message/default/<action>',
                    'mall/<action:(create|update|delete|shelve|unshelve|upload)>' => 'mall/default/<action>',
                    'pubg/<action:(create|update|delete)>' => 'pubg/default/<action>',
                    'finance/<action:(create|update|delete)>' => 'finance/default/<action>',
            ],
        ],
        'assetManager' => [
            'bundles' => [
                'yii\web\JqueryAsset' => [
                    'js' => [],  // 去除 jquery.js
                    'sourcePath' => null,  // 防止在 frontend/web/asset 下生产文件
                ],
            ],
        ],
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=172.17.0.11;dbname=app_web',
            'username' => 'app_web',
            'password' => 'web_app!@#',
            'charset' => 'utf8mb4',
        ],
       
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    $config['components']['cache'] = [
        'class' => 'yii\caching\FileCache',
    ];
    $config['components']['db'] = [
        'class' => 'yii\db\Connection',
        'dsn' => 'mysql:host=192.168.1.2;dbname=app_web',
        'username' => 'starrank',
        'password' => '^Y&U*I(O',
        'charset' => 'utf8mb4',
    ];
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
         'allowedIPs' => ['127.0.0.1', '::1' , '192.168.1.*'],
    ];
    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        // 'allowedIPs' => ['127.0.0.1', '::1','192.168.1.*'],
    ];
     $config['params']['remoteApiDomain'] = 'http://192.168.1.2:8888/manager';
     $config['params']['dataApiDomain'] = 'http://192.168.1.2:8080';
     $config['params']['pubgApiDomain'] = 'http://192.168.1.2:8000';
     $config['params']['pubgSettlementApi'] = 'http://test.usercenter.baidourank.com';
     $config['params']['pubgVersionApi'] = 'http://192.168.1.97:3001';
     $config['params']['betDomain'] = 'http://develop.newadmin.baidourank.com';
     $config['params']['betMenuIds'] = [50];
     $config['params']['financeMenuIds'] = 32;

}

return $config;
