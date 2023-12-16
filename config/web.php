<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'name' => 'shop',
    'language' => 'ru-RU',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
    ],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'Sergey',
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ]
        ],

        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => \yii\symfonymailer\Mailer::class,
            'viewPath' => '@app/mail',
            // send all mails to a file by default.
            'useFileTransport' => true,
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
        'db' => $db,
        'urlManager' => [
            'enablePrettyUrl' => true,
            'enableStrictParsing' => true,
            'showScriptName' => false,
            'rules' => [
// При добавлении функции, добавляем сюда ее. [метод] [/api/...] => [названия contoller]/[название функции] k-pyatnitca.xn--80ahdri7a.site  
'POST register' => 'users/create',                           // Регистрация
'POST login' => 'users/login',                               // Авторизация
'GET products' => 'products/products',                               // Получить список всех товаров
'GET product/<id_product:\d+>' => 'products/product',                  // Получить один товар
'POST orders/add/<id_product:\d+>' => 'orders/add',            // Купить товар
'DELETE orders/delete/<order_id:\d+>' => 'orders/delete',    // Удаление из корзины !!!
'POST product/add' => 'products/add',                            // Добавление товара в систему
'DELETE product/delete/<id_product:\d+>' => 'products/delete',      // Удаление товара из системы
'POST product/update/<id_product:\d+>' => 'products/update',        // Изменение данных об товаре 
'GET user/<id_user:\d+>' => 'users/user',                    // Получение данных пользователя
'GET users' => 'user/users',                                // Получения данных всех пользователей
'GET user/orders/<id_user:\d+>' => 'users/orders',           // Просмотр корзины !!!  // Просмотр корзины !!!
            ],
        ],
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        'allowedIPs' => ['127.0.0.1', '::1', '*'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        'allowedIPs' => ['127.0.0.1', '::1', '*'],
    ];
}

return $config;